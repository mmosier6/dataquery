#define MAX_WATCH_LINE_LENGTH 2000
#define NUMBER_OF_WATCH_KEYS 13
#define NUMBER_OF_SEARCH_CATS 3
#define DEBUG 0

void parseWatchCSV(char *fname, char *sdt, char *edt, char *search1, char *search2, char *search3);
void copyline(FILE* fp, size_t size);
//void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match);
void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search_cat[NUMBER_OF_SEARCH_CATS], char *search_criteria[NUMBER_OF_SEARCH_CATS], char *match);
int checkWatchSearchCategory(char *cat);

void parseWatchCSV(char *fname,  char *sdt, char *edt, char *search1, char *search2, char *search3){
  FILE *fp;
  char st[MAX_WATCH_LINE_LENGTH],  st2[MAX_WATCH_LINE_LENGTH], delim[2];;
  int stlen=0, l = 0, ll, i, s_cmp, key_count, r_count = 0, debug = 0;
  int z, type_search = 0, pds_search = 0, st_search = 0, fips_search = 0, cwa_search = 0, cc;
  char keys[NUMBER_OF_WATCH_KEYS][100], match[MAX_WATCH_LINE_LENGTH];
 	char *search, *m, search_cat[NUMBER_OF_SEARCH_CATS][100], search_criteria[NUMBER_OF_SEARCH_CATS][5*15];
 	char search_cat_copy[NUMBER_OF_SEARCH_CATS][100], search_criteria_copy[NUMBER_OF_SEARCH_CATS][5*15], *search_cat_ptr[NUMBER_OF_SEARCH_CATS], *search_criteria_ptr[NUMBER_OF_SEARCH_CATS];
 	
 	if(DEBUG == 1){printf("Processing %s\n", fname);}
 	
	//Get search criteria
	for(z = 1; z < NUMBER_OF_SEARCH_CATS + 1; z++){
		if(z == 1){
			//No search 1 criteria in command-line args
			if(search1[0] == '\0'){
				continue;
			}else{
				search = search1;
			}
		}else if(z == 2){
			//No search 2 criteria in command-line args
			if(search2[0] == '\0'){
				continue;
			}else{
				search = search2;
			}

		}else if(z == 3){
			//No search 3 criteria in command-line args
			if(search3[0] == '\0'){
				continue;
			}else{
				search = search3;
			}
		}
	
		//search_cat[z-1] = strtok(search, "=");
		strcpy(search_cat[z-1], strtok(search, "="));
		//search_criteria[z-1] = strtok(NULL, "=");
		strcpy(search_criteria[z-1],strtok(NULL, "=")); 
	}
	
	for(i = 1; i < NUMBER_OF_SEARCH_CATS+1; i++){
		if(DEBUG == 1){printf("Search %i: %s = %s\n", i, search_cat[i-1], search_criteria[i-1]);}
	}
	if(DEBUG == 1){printf("Start datetime: %s\n", sdt);}
	if(DEBUG == 1){printf("End datetime: %s\n", edt);}

	fp = fopen(fname, "rt");
 	
 	while(!feof(fp)) {
		//printf("Line Number: %i\n", l);
		fgets(st, MAX_WATCH_LINE_LENGTH, fp);
		stlen = strlen(st);
		
		//Remove newline
		st[strlen(st)-1] = 0;
    if(DEBUG == 1){printf("Line (%i): %s\n", l, st);}
    
   	//Copy in search categories 
   	for(i = 0; i < NUMBER_OF_SEARCH_CATS; i++){
			strcpy(search_cat_copy[i], search_cat[i]);
			strcpy(search_criteria_copy[i], search_criteria[i]);
			//strcpy(search_cat[i], search_cat_copy[i]);
			//strcpy(search_criteria[i], search_criteria_copy[i]);
		}	

		if(st[0] == '\0'){
			l++;
			st[0] = '\0';
			continue;
		}
		s_cmp = strncmp(st, "WATCH_NUM", 8);		//On first line

		if (s_cmp != 0) {
			match[0] = '\0';
			for(i = 0; i < NUMBER_OF_SEARCH_CATS; i++){
				if(DEBUG == 1){printf("Search Category: %s, criteria here: %s\n", search_cat_copy[i], search_criteria_copy[i]);}
				search_cat_ptr[i] = search_cat_copy[i];
				search_criteria_ptr[i] = search_criteria_copy[i];
			}	
			parseWatchLine(keys, st, sdt, edt, search_cat_ptr, search_criteria_ptr, match);
      if(DEBUG == 1){printf("Match: %s\n", match);}
			if(strcmp(match, "") == 0){
				l++;
				st[0] = '\0';
				continue;
			}else if(strcmp(match, "-9999") == 0){
				printf("\tError parsing report. Exiting...\n");
				return;
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

//void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match) {
void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search_cat[NUMBER_OF_SEARCH_CATS], char *search_criteria[NUMBER_OF_SEARCH_CATS], char *match){
  int i, ii, z, test[NUMBER_OF_WATCH_KEYS], cc;
  char *s[NUMBER_OF_WATCH_KEYS]; 
  double wsdt, wedt, s_sdt, s_edt;
  char search[255], *testchar, delim;
 	char *search_criteria_list[15], *search_crit_orig;
 	char out[MAX_WATCH_LINE_LENGTH+100], out2[MAX_WATCH_LINE_LENGTH+100];
 			
  // Split CSV into char arrayß
  //printf("\nLine: %s\n", st);
	
	s[0] = strtok(st, ",");
  //strcpy(csv[0], strtok(st, ","));
	//printf("%s => %s\n", keys[0], s[0]);
	test[0] = 1;
  for(i=1;i<NUMBER_OF_WATCH_KEYS;i++) {
   	s[i] = strtok(NULL, ",");
   	test[i] = -1; 
  }  
  
  for(i=1;i<NUMBER_OF_WATCH_KEYS;i++) {
  	if(DEBUG == 1){printf("Watch key: %s\n", keys[i]);}  	
   	//Check times
   	//Start time
   	if(strcmp("SEL_ISSUE_DT", keys[i]) == 0){
   		test[i] = 0;
   		if(strcmp(sdt, "any") == 0){
   			test[i] = 1;
   			continue;	
   		}else{
				wsdt = atof(s[i]);
				s_sdt= atof(sdt);
				if(wsdt >= s_sdt){test[i] = 1;}
				else{test[i] = 0;}
				continue;
   		}
   	}
   	
   	//End time
   	if(strcmp("SEL_EXPIRE_DT", keys[i]) == 0){
   		test[i] = 0;
   		if(strcmp(edt, "any") == 0){
   			test[i] = 1;
   			continue;	
   		}else{
				wedt = atof(s[i]);
				s_edt= atof(edt);
				if(wedt <= s_edt){test[i] = 1;}
				else{test[i] = 0;}
				continue;
   		}
   	}
 
   	
   	//Check all other search criteria
		if(checkWatchSearchCategory(keys[i]) != 1){
			test[i] = 1;	
			continue;
		}
   	for(z = 0; z < NUMBER_OF_SEARCH_CATS; z++){
   		if(strcmp(keys[i], search_cat[z]) == 0){
   			if(DEBUG == 1){printf("\tSearch Category: %s\n", search_cat[z]);}
   			test[i] = 0;
   			//printf("Match!!\n");
   			//search_crit_orig = search_criteria[z];
   			if(DEBUG == 1){printf("\t\tSearch criteria (%i): %s\n", z, search_criteria[z]);}
   			if(strcmp(search_criteria[z], "ALL") == 0){
   				test[i] = 1;
   				break; //It is assumed that a key will NOT match more than one search criteria
   			}else{
   				//Count commas in search criteria
					delim =  ',';
					cc = 0;
					for(ii = 0; ii < strlen(search_criteria[z]); ii++){
						testchar = &search_criteria[z][ii];
						if(*testchar == delim){
							cc++;
						}
					}
					//printf("Comma count: %i\n", cc);
					//Split search criteria into tokens
					if(cc > 15){
						if(DEBUG == 1){printf("\tERROR: Exceed maximum number of search list values (15). Exiting\n");}
						strcpy(match, "-9999");
						return;
					}else if (cc > 0){
						search_criteria_list[0] = strtok(search_criteria[z], ",");
						for(ii = 1; ii < cc+1; ii++){
							search_criteria_list[ii] = strtok(NULL, ",");
						}
					}else{
						search_criteria_list[0] = search_criteria[z];
					}
					//At this point search_criteria_list should contain each search criteria for the 
					//given category (i.e. if category is ST then it will have 0: TX, 1: OK, 2: KS)
					//as listed by on the command line 
					for(ii = 0; ii < cc+1; ii++){
						if(DEBUG == 1){printf("\t\t\tSearching for %s\n", search_criteria_list[ii]);} 
						if(strstr(s[i], search_criteria_list[ii]) != NULL){
							if(DEBUG == 1){printf("\t\t\t\t Match (%s) found!\n", strstr(s[i], search_criteria_list[ii]));}
							test[i] = 1;
						}	
					}
   			}
   		}
   	}
   	
   	if(test[i] == 0){break;}
  }	
  
  for(i=0;i<NUMBER_OF_WATCH_KEYS;i++) {
  	if(DEBUG == 1){printf("%s = %i\n", keys[i], test[i]);}
  	if(test[i] == 0){
  		strcpy(match, "");
			return;
  	}else{
		 	if(i == 0){
				sprintf(out, "{\"%s\":\"%s\"", keys[i],s[i]);
			}else{
				sprintf(out2, ",\"%s\":\"%s\"", keys[i],s[i]);
				strcat(out, out2);
			}
  	}
  }
  
	sprintf(out2, "}");
	strcat(out, out2);
	strcpy(match, out);
	
	return;
}

int checkWatchSearchCategory(char *cat){
	char cats[5][255];
	int i;

	strcpy(cats[0],"TYPE");
	strcpy(cats[1],"PDS");
	strcpy(cats[2],"ST");
	strcpy(cats[3],"FIPS");
	strcpy(cats[4],"CWA");

	for(i = 0; i < 5; i++){
		if(strcmp(cat, cats[i]) == 0){
			return 1;
		}
	}

	return 0;
}
