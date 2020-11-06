#define MAX_WATCH_LINE_LENGTH 2000
#define NUMBER_OF_WATCH_KEYS 13

void parseWatchCSV(char *fname, char *sdt, char *edt, char *search1, char *search2, char *search3);
void copyline(FILE* fp, size_t size);
void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match);

void parseWatchCSV(char *fname,  char *sdt, char *edt, char *search1, char *search2, char *search3){
  FILE *fp;
  char st[MAX_WATCH_LINE_LENGTH],  st2[MAX_WATCH_LINE_LENGTH], delim[2];;
  int stlen=0, l = 0, ll, i, s_cmp, key_count, r_count = 0;
  char keys[NUMBER_OF_WATCH_KEYS][100], match[MAX_WATCH_LINE_LENGTH];

  char *m;

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
      //printf("Search 1: %s\n", search1);
      //printf("Search 2: %s\n", search2);
      //printf("Search 3: %s\n", search3);
      //printf("Start datetime: %s\n", sdt);
      //printf("End datetime: %s\n", edt);
			parseWatchLine(keys, st, sdt, edt, search1, search2, search3, match);
      //continue;
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

void parseWatchLine(char keys[NUMBER_OF_WATCH_KEYS][100], char *st, char *sdt, char *edt, char *search1, char *search2, char *search3, char *match) {
  int i;
  char csv[13][255];
  char orig[MAX_WATCH_LINE_LENGTH];

  // Split CSV into char arrayß
  strcpy(orig, st);

  int count = 0;

  //printf("Line: %s\n", st);

  strcpy(csv[0], strtok(st, ","));

  for(i=1;i<NUMBER_OF_WATCH_KEYS;i++) {
    strcpy(csv[i], strtok(NULL, ","));
  }

  for(i=0;i<NUMBER_OF_WATCH_KEYS;i++) {
    //printf("%s => %s\n", keys[i], csv[i]);
  }

  printf("%s => %s\n", keys[NUMBER_OF_WATCH_KEYS-1], csv[NUMBER_OF_WATCH_KEYS-1]);

  return;

}
