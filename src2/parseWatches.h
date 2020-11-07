#define MAX_WATCH_LINE_LENGTH 2000
#define NUMBER_OF_WATCH_KEYS 13
#define NUMBER_OF_SEARCH_CATS 3

void parseWatchCSV(char *fname, char *sdt, char *edt, char *search1, char *search2, char *search3);
void copyline(FILE* fp, size_t size);
//void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match);
void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search_cat[NUMBER_OF_SEARCH_CATS], char *search_criteria[NUMBER_OF_SEARCH_CATS], char *match);
int checkWatchSearchCategory(char *cat);

void parseWatchCSV(char *fname,  char *sdt, char *edt, char *search1, char *search2, char *search3){
  FILE *fp;
  char st[MAX_WATCH_LINE_LENGTH],  st2[MAX_WATCH_LINE_LENGTH], delim[2];;
  int stlen=0, l = 0, ll, i, s_cmp, key_count, r_count = 0;
  int z, type_search = 0, pds_search = 0, st_search = 0, fips_search = 0, cwa_search = 0, cc;
  char keys[NUMBER_OF_WATCH_KEYS][100], match[MAX_WATCH_LINE_LENGTH];
 	char *search, *m, *search_cat[NUMBER_OF_SEARCH_CATS], *search_criteria[NUMBER_OF_SEARCH_CATS];
 	
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
	
		search_cat[z-1] = strtok(search, "=");
		search_criteria [z-1] = strtok(NULL, "=");
	}
	
  printf("Processing %s\n", fname);

  fp = fopen(fname, "rt");

  while(!feof(fp)) {
		//printf("Line Number: %i\n", l);
		fgets(st, MAX_WATCH_LINE_LENGTH, fp);
		stlen = strlen(st);
		//Remove newline
		st[strlen(st)-1] = 0;
    //printf("\tline (%i): %s\n", l, st);
		//strcpy(st2, st);

		if(st[0] == '\0'){
			l++;
			st[0] = '\0';
			continue;
		}
		s_cmp = strncmp(st, "watch_num", 8);		//On first line

		if (s_cmp != 0) {
			match[0] = '\0';
			for(i = 1; i < NUMBER_OF_SEARCH_CATS+1; i++){
      	printf("Search %i: %s = %s\n", i,search_cat[i-1], search_criteria[i-1]);
      }
      printf("Start datetime: %s\n", sdt);
      printf("End datetime: %s\n", edt);
			//parseWatchLine(keys, st, sdt, edt, search1, search2, search3, match);
			parseWatchLine(keys, st, sdt, edt, search_cat, search_criteria, match);
      //printf("Match: %s\n", match);

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
  char search[255], *testchar, delim;
 	char *search_criteria_list[15];
 			
  // Split CSV into char arrayß
  printf("Line: %s\n", st);
	
	s[0] = strtok(st, ",");
  //strcpy(csv[0], strtok(st, ","));
	printf("%s => %s\n", keys[0], s[0]);
	test[0] = 1;
  for(i=1;i<NUMBER_OF_WATCH_KEYS;i++) {
   	s[i] = strtok(NULL, ",");
  }  
  
  for(i=1;i<NUMBER_OF_WATCH_KEYS;i++) {
  	test[i] = 1;  	
   	//Check times
   	
   	//Check all other search criteria
		if(checkWatchSearchCategory(keys[i]) != 1){continue;}
   	for(z = 0; z < NUMBER_OF_SEARCH_CATS; z++){
   		//printf("%s\n", search_cat[z]);
   		if(strcmp(keys[i], search_cat[z]) == 0){
   			test[i] = 0;
   			//printf("Match!!\n");
   			printf("%s\n", search_criteria[z]);
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
					printf("Comma count: %i\n", cc);
					//Split search criteria into tokens
					if(cc > 15){
						printf("\tERROR: Exceed maximum number of search list values (15). Exiting\n");
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
						printf("%s\n", search_criteria_list[ii]); 
						if(strstr(s[i], search_criteria_list[ii]) != NULL){
							printf("%s\n", strstr(s[i], search_criteria_list[ii]));
							test[i] = 1;
						}	
					}
   			}
   		}
   	}
  }	
  
  for(i=1;i<NUMBER_OF_WATCH_KEYS;i++) {
  	printf("%s = %i\n", keys[i], test[i]);
  }
  
  exit(1); 
    
    //strcpy(csv[i], strtok(NULL, ",")); 
    //printf("%s => %s\n", keys[i], csv[i]);

    //watch_num, type, pds, sel_issue_dt, sel_issue_epoch, sel_expire_dt, sel_expire_epoch, threats, ST, FIPS, CWA, areas, summary
    //Test to see if type matches
    
  //printf("%s => %s\n", keys[NUMBER_OF_WATCH_KEYS-1], csv[NUMBER_OF_WATCH_KEYS-1]);
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
