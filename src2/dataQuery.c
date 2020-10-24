#include <stdio.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>

void parseReportCSV(char keys[10][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match);
float distance(float lat1, float lon1, float lat2, float lon2);
void parseDT(char dt[], char yy[4], char mm[2], char dd[2], char hh[2], char mi[2], char sc[2]);
int checkSearchCategory(char *rt, char *cat);
void substring(char s[], char sub[], int p, int l);

int main(int argc, const char *argv[]) {
	FILE *fp;
	char st[255], st2[255], *csv, *testchar, delim[2];
	char type[255], rtype[255], sdt[255], edt[255], search1[255], search2[255], search3[255];
	int first=1, stlen=0, i, j, ii, jj;
	int byear=1950, eyear=2017;
	float totarea;
	int s_cmp, l, ll;
	int key_count, r_count = 0;
	char keys[10][100], match[255];

	// Test for correct command line args
	// Arguments:
	// dataQuery type [reports, watch, outlook] type2 [if type = reports: all, wind, hail, tornado] start_dt [YYYYMMDDHHmmSS] end_dt [YYYYMMDDHHmmSS] search1 search2 search3
	if (argc < 4) {
		printf("ERROR(1):  Improper command line arguments.  Aborting...\n");
		exit(1);
	}else{
		// Test for correct command line args
		strcpy(type, argv[1]);
		strcpy(sdt, argv[2]);
		strcpy(edt,argv[3]);
		//printf("%s\n", type);
		//printf("%s\n", sdt);
		//printf("%s\n", edt);

		if((argc-1) > 3){
			//printf("%s\n", argv[4]);
			strcpy(search1, argv[4]);
		}else{
			search1[0] = '\0';
		}
		if((argc-1) > 4){
			//printf("%s\n", argv[5]);
			strcpy(search2, argv[5]);
		}else{
			search2[0] = '\0';
		}
		if((argc-1) > 5){
			//printf("%s\n", argv[6]);
			strcpy(search3, argv[6]);
		}else{
			search3[0] = '\0';
		}
		//printf("\n");
	}

	// Open file
	//fp = fopen("../../../../data/work/report_collection_2019.csv", "rt");
	//fp = fopen("/vdevweb2/devweb/public_html2/data/work/report_collection_2019.csv", "rt");
	//fp = fopen("/var/www/html/devweb/public_html2/data/work/report_collection_2019.csv", "rt");
	fp = fopen("./work/combined_report_file.csv", "rt");
	if(fp == NULL){
		//perror("Failed: ");
		fp = fopen("../work/combined_report_file.csv", "rt");
		if(fp == NULL){
			perror("Failed: ");
			return 0;
		}
	}
	l = 0;

	while(!feof(fp)) {
		//printf("Line Number: %i\n", l);
		fgets(st, 255, fp);
		stlen = strlen(st);
		//Remove newline
		st[strlen(st)-1] = 0;
		strcpy(st2, st);
		//printf("\tstring (%i): %s\n", l, st);
		if(st[0] == '\0'){
			l++;
			st[0] = '\0';
			continue;
		}
		s_cmp = strncmp(st, "LOCATION", 8);		//On first line

		if (s_cmp != 0) {
			match[0] = '\0';
			parseReportCSV(keys, st, sdt, edt, search1, search2, search3, match);

			if(strcmp(match, "") == 0){
				l++;
				st[0] = '\0';
				continue;
			}else if(strcmp(match, "-9999") == 0){
				printf("\tError parsing report. Exiting...\n");
				return 0;
			}else{
				r_count++;
				if(r_count == 1){
					printf("[%s", match);
				}else if(r_count > 1){
					printf(",%s", match);
				}
			}
		}else{
			//Get csv keys from first line
			key_count = 0;
			strcpy(delim, ",");
			ll = strlen(st);
			for(i = 0; i < ll; i++){
				if(st[i] == delim[0]){
					key_count++;
				}
			}
			strcpy(keys[0], strtok(st, ","));
			for(i = 1; i < key_count+1; i++){
				strcpy(keys[i], strtok(NULL, ","));
			}
		}

		l++;
		st[0] = '\0';
	}
	if(r_count == 0){
		printf("[]");
	}else{
		printf("]");
	}
}

// Parse a single CSV line into a JSON array element
void parseReportCSV(char keys[10][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match) {
	char csv[30][100], orig[255], stx[20], out[255], out2[255];
	int i, j, z, first2=1, keep=0, cc;
	//char sy[4], sm[2], sd[2], sh[2], sn[2], ss[2];
	char sy[5], sm[3], sd[3], sh[3], sn[3], ss[3];
	char ey[4], em[2], ed[2], eh[2], en[2], es[2];
	char tmpstr[4], search_cat[255], search_criteria[255],search_criteria_list[15][255], *testchar, delim, s[255];
	int ry, rm, rd, rh, rn, rs;
	int keynum, rtype_match = 0, dt_match = 0, s1_match = 0, s2_match = 0, s3_match = 0;
	double rdt, s_sdt, s_edt;
	size_t n;

	// Split CSV into char array
	strcpy(orig,st);

	strcpy(csv[0], strtok(st, ","));

	for(i=1;i<10;i++) {
		strcpy(csv[i], strtok(NULL, ","));
	}

	//Search through various criteria to find matches
	//Is report within datetime range?

	//Get report date/time from string
	keynum = -1;
	for(i = 0; i < 10; i++){
		if(strcmp(keys[i], "DT") == 0){
			keynum = i;
		}
	}

	parseDT(csv[keynum], sy, sm, sd, sh, sn, ss);

	if(sy[0] == '\0') {
		printf("here11\n");
		strcpy(match, "-9999");
		return;
	}
	//printf("Report Datetime => Year:%s, Month: %s, Day:%s, Hour:%s, Minute: %s, Second: %s\n", sy, sm, sd, sh, sn, ss);

	rdt = atof(csv[keynum]);
	if((strcmp(sdt, "any") == 0) && (strcmp(edt, "any") == 0)){
		dt_match = 1;
	}else if((strcmp(sdt, "any") == 0)){
		s_edt = atof(edt);
		if(rdt <= s_edt){dt_match = 1;}
	}else if((strcmp(edt, "any") == 0)){
		if(rdt >= s_sdt){dt_match = 1;}
	}else{
		s_sdt = atof(sdt);
		s_edt = atof(edt);
		if((rdt >= s_sdt) && (rdt <= s_edt)){
			dt_match = 1;
		}
	}

	if(dt_match == 1){
		//FYI (by RMM 10/12/2020) - Can only handle EXACT matches right now
		//Second: Check each 'search' criteria
		for(z = 1; z < 4; z++){
			if(z == 1){
				//No search 1 criteria in command-line args
				if(search1[0] == '\0'){
					s1_match = 1;
					continue;
				}else{
					strcpy(s, search1);
				}
			}else if(z == 2){
				//No search 2 criteria in command-line args
				if(search2[0] == '\0'){
					s2_match = 1;
					continue;
				}else{
					strcpy(s, search2);
				}

			}else if(z == 3){
				//No search 3 criteria in command-line args
				if(search3[0] == '\0'){
					s3_match = 1;
					continue;
				}else{
					strcpy(s, search3);
				}
			}

			//Second: Check each 'search' criteria
			strcpy(search_cat, strtok(s, "="));
			strcpy(search_criteria, strtok(NULL, "="));
			if(checkSearchCategory("report", search_cat) == 1){
				//Count commas to get all the search variables
				delim =  ',';
				cc = 0;
				for(i = 0; i < strlen(search_criteria); i++){
					testchar = &search_criteria[i];
					if(*testchar == delim){
						cc++;
					}
				}
				if(cc > 15){
					printf("\tERROR: Exceed maximum number of search list values (15). Exiting\n");
					strcpy(match, "-9999");
					return;
				}else if (cc > 0){
					strcpy(search_criteria_list[0], strtok(search_criteria, ","));
					for(i = 1; i < cc+1; i++){
						strcpy(search_criteria_list[i], strtok(NULL, ","));
					}
				}else{
					strcpy(search_criteria_list[0], search_criteria);
				}
				//Get key of search category
				keynum = -1;
				for(i = 0; i < 10; i++){
					if(strcmp(keys[i], search_cat) == 0){
						keynum = i;
					}
				}
				//printf("Keynum: %i\n", keynum);
				//printf("Key: %s\n", keys[keynum]);
				//printf("Value: %s\n", csv[keynum]);
				//See if data matches any values in search criteria list
				for(i = 0; i < cc+1; i++){
					//printf("\tSearch criteria: %s\n", search_criteria_list[i]);
					if(strcmp("ALL",search_criteria_list[i]) == 0){
						if(z == 1){s1_match = 1;}
						if(z == 2){s2_match = 1;}
						if(z == 3){s3_match = 1;}
					}else if(strcmp(csv[keynum],search_criteria_list[i]) == 0){
						if(z == 1){s1_match = 1;}
						if(z == 2){s2_match = 1;}
						if(z == 3){s3_match = 1;}
					};
				}
			}else{
				printf("\tInvalid 'search' category: %s. Exiting\n", search_cat);
				strcpy(match, "-9999");
				return;
			}

		}
		//printf("dt_match: %i\ns1_match: %i\ns2_match: %i\ns3_match: %i\n", dt_match, s1_match, s2_match, s3_match) ;

		if((dt_match == 1) && (s1_match == 1) && (s2_match == 1) && (s3_match == 1)){
			for(j = 0; j < 10; j++){
				if(j == 0){
					sprintf(out, "{\"%s\":\"%s\"", keys[j],csv[j]);
				}else{
					sprintf(out2, ",\"%s\":\"%s\"", keys[j],csv[j]);
					strcat(out, out2);
				}
			}
			sprintf(out2, "}");
			strcat(out, out2);
			strcpy(match, out);
			return;
		}else{
			strcpy(match, "");
			return;
		}
	}else{
		strcpy(match, "");
		return;
	}
}

int checkSearchCategory(char *rt, char *cat){
	char report_cats[10][255];
	int i;

	if(strcmp(rt, "report") == 0){
		strcpy(report_cats[0],"LOCATION");
		strcpy(report_cats[1],"ST");
		strcpy(report_cats[2],"MAGNITUDE");
		strcpy(report_cats[3],"TYPE");
		strcpy(report_cats[4],"FIPS");
		strcpy(report_cats[5],"CWA");
		strcpy(report_cats[6],"INJURY");
		strcpy(report_cats[7],"FATALITIES");
		strcpy(report_cats[8],"DT");
		strcpy(report_cats[9],"COUNTY");

		for(i = 0; i < 10; i++){
			if(strcmp(cat, report_cats[i]) == 0){
				return 1;
			}
		}

	}

	return 0;
}


float distance(float lat1, float lon1, float lat2, float lon2) {
        float torad = 3.1415926 / 180.0;
        float R = 6371;

        lat1 *= torad;
        lon1 *= torad;
        lat2 *= torad;
        lon2 *= torad;

        float dlon = lon2 - lon1;
        float dlat = lat2 - lat1;
        float aaa = sin(dlat/2.0) * sin(dlat/2.0) + cos(lat1) * cos(lat2) * sin(dlon/2.0) * sin(dlon/2.0);
        float ccc = 2.0 * atan2(sqrt(aaa), sqrt(1-aaa));
        float dist = (R * ccc);
        return dist ;
}

void parseDT(char dt[], char yy[5], char mm[3], char dd[3], char hh[3], char mi[3], char sc[3]){
	//Test datetime string format
	int dtlen;

	dtlen = strlen(dt);
	if(dtlen != 14){
		printf("\tInvalid datetime format. Expected format: YYYYMMDDHHmmSS\n");
		yy[0] = '\0';
	}else{
		//Year
		substring(dt, yy, 1, 4);
		//Month
		substring(dt, mm, 5, 2);
		//Day
		substring(dt, dd, 7, 2);
		//Hour
		substring(dt, hh, 9, 2);
		//Minute
		substring(dt, mi, 11, 2);
		//Second
		substring(dt, sc, 13, 2);
	}
}

void substring(char s[], char sub[], int p, int l){
	int c = 0;
	while(c < l){
		sub[c] = s[p+c-1];
		c++;
	}

	sub[c] = '\0';

}
