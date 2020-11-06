#include <stdio.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>
#include <unistd.h>

float distance(float lat1, float lon1, float lat2, float lon2);
void substring(char s[], char sub[], int p, int l);

#include "parseReports.h"

int main(int argc, const char *argv[]) {
	//char st[255], st2[255], *csv, *testchar, delim[2];
	char dtype[255], rtype[255], sdt[255], edt[255], search1[255], search2[255], search3[255], fname[255];
	//int first=1, stlen=0, i, j, ii, jj;
	//int byear=1950, eyear=2017;
	//float totarea;
	//int s_cmp, l, ll;
	//int key_count, r_count = 0;
	//char keys[10][100], match[255];

	// Test for correct command line args
	// Arguments:
	// dataQuery type [reports, watch, outlook] type2 [if type = reports: all, wind, hail, tornado] start_dt [YYYYMMDDHHmmSS] end_dt [YYYYMMDDHHmmSS] search1 search2 search3
	if (argc < 4) {
		printf("ERROR(1):  Improper command line arguments.  Aborting...\n");
		exit(1);
	}else{
		// Test for correct command line args
		strcpy(dtype, argv[1]);
		strcpy(sdt, argv[2]);
		strcpy(edt, argv[3]);
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


	if(strcmp(dtype, "reports") == 0){
		//printf("Finding Reports\n");
		//Test for reports file
		strcpy(fname, "./work/combined_report_file.csv");
		if( access( fname, F_OK ) != -1 ) {
    	// file exists
			//printf("%s found!\n", fname);
		} else {
			strcpy(fname, "../work/combined_report_file.csv");
			if( access( fname, F_OK ) != -1 ) {
				// file exists
				//printf("%s found!\n", fname);
				parseReportCSV(fname, sdt, edt, search1, search2, search3);
			} else {
				printf("ERROR: %s not found. Exiting...\n", fname);
				return 0;
			}
		}
	}

	return 1;
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

void substring(char s[], char sub[], int p, int l){
	int c = 0;
	while(c < l){
		sub[c] = s[p+c-1];
		c++;
	}

	sub[c] = '\0';

}
