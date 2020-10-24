#include <stdio.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>

void parseCSV(int *first, char *st, float clat, float clon, float radius);
float distance(float lat1, float lon1, float lat2, float lon2);

// Array of tornado stats by Fscale
float torCountByFscale[6][10];
float torCountByMonth[13][10];
float torCountByHour[25][10];
int vioTornadoDays=0, allTornadoDays=0, killerTornadoDays=0, strongTornadoDays=0;
int vioTornadoLast=0, allTornadoLast=0, killerTornadoLast=0, strongTornadoLast=0;
int yda, tda;

int main(int argc, const char *argv[]) {
	FILE *fp;
	char st[255], *csv;
	int first=1, stlen=0, i, j;
	int byear=1950, eyear=2017;
	float totarea;

	// Test for correct command line args
	if (argc < 4) {
		printf("ERROR(1):  Improper command line arguments.  Aborting...\n");
		exit(1);
		}
	/*
	float clat, clon, radius;
	int fail=0;
	clat = atof(argv[1]);
	clon = atof(argv[2]);
	radius = atof(argv[3]);
	if ((clat < 20) || (clat > 50)) fail=1;
	if ((clon < -130) || (clon > -60)) fail=1;
	if (fail == 1) {
		printf("ERROR:  Improper command line arguments.  Aborting...\n");
		exit(1);
		}

	// Initialize arrays
	for(i=0;i<6;i++) {
		for(j=0;j<10;j++) {
			torCountByFscale[i][j] = 0;
			}
		}
	for(i=0;i<12;i++) {
		for(j=0;j<10;j++) {
			torCountByMonth[i][j] = 0;
			}
		}
	*/	
	// Open file
	//fp = fopen("../../data/1950-2017_torn.csv", "rt");
	fp = fopen("/vdevweb2/devweb/public_html2/data/work/report_collection_2020.csv", "rt");

	// Compute total area of query zone
	totarea = 3.14159F * (radius * radius);
	
	// Loop through each entry, producing JSON output
	// printf("{\n\"Tornadoes\": [");
	while(!feof(fp)) {
		fgets(st, 255, fp);
		stlen = strlen(st);
		printf("%s\n", st);
		if (stlen > 50) {
			parseCSV(&first, st, clat, clon, radius);
			}
		}
	/*
	printf("\n],\n\"QueryInformation\": {\n");
	printf("   \"Years\":        [ %4d, %4d ],\n", byear, eyear);
	printf("   \"LatLon\":       [ %6.2f, %6.2f ],\n", clat, clon);
	printf("   \"TotalAreaKm2\": %6.1f,\n", totarea);
	printf("   \"RadiusKm\":     %6.0f\n", radius);
	printf("},\n\"MiscStatistics\": {\n");
	printf("   \"AllTornadoDays\": %d,\n", allTornadoDays);
	printf("   \"StrongTornadoDays\": %d,\n", strongTornadoDays);
	printf("   \"ViolentTornadoDays\": %d,\n", vioTornadoDays);
	printf("   \"KillerTornadoDays\": %d\n", killerTornadoDays);
	printf("},\n\"StatisticsByFscale\": {\n");
	printf("   \"TotalTornadoes\": [ %5.0f, %5.0f, %5.0f, %5.0f, %5.0f, %5.0f ],\n", torCountByFscale[0][0], torCountByFscale[1][0], torCountByFscale[2][0], torCountByFscale[3][0], torCountByFscale[4][0], torCountByFscale[5][0], torCountByFscale[6][0]);
	printf("   \"Injuries\":       [ %5.0f, %5.0f, %5.0f, %5.0f, %5.0f, %5.0f ],\n", torCountByFscale[0][1], torCountByFscale[1][1], torCountByFscale[2][1], torCountByFscale[3][1], torCountByFscale[4][1], torCountByFscale[5][1], torCountByFscale[6][1]);
	printf("   \"Fatalities\":     [ %5.0f, %5.0f, %5.0f, %5.0f, %5.0f, %5.0f ],\n", torCountByFscale[0][2], torCountByFscale[1][2], torCountByFscale[2][2], torCountByFscale[3][2], torCountByFscale[4][2], torCountByFscale[5][2], torCountByFscale[6][2]);
	printf("   \"AreaSqKm\":       [ %5.0f, %5.0f, %5.0f, %5.0f, %5.0f, %5.0f ]\n", torCountByFscale[0][3], torCountByFscale[1][3], torCountByFscale[2][3], torCountByFscale[3][3], torCountByFscale[4][3], torCountByFscale[5][3], torCountByFscale[6][3]);
	printf("},\n\"StatisticsByMonth\": {\n");
	printf("   \"TotalTornadoes\": [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][0]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"F2+Tornadoes\":   [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][1]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"F4+Tornadoes\":   [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][2]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"AreaSqKm\":       [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][3]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"F2+AreaSqKm\":    [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][6]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"Injuries\":       [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][4]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"Fatalities\":     [ ");
	first = 1;
	for(i=1;i<13;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByMonth[i][5]);
		first = 0;
		}
	printf(" ]\n");

	printf("},\n\"StatisticsByHour\": {\n");
	printf("   \"TotalTornadoes\": [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][0]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"F2+Tornadoes\":   [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][1]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"F4+Tornadoes\":   [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][2]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"AreaSqKm\":       [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][3]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"F2+AreaSqKm\":    [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][6]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"Injuries\":       [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][4]);
		first = 0;
		}
	printf(" ], \n");

	printf("   \"Fatalities\":     [ ");
	first = 1;
	for(i=0;i<24;i++) {
		if (first == 0) printf(", ");
		printf("%5.0f", torCountByHour[i][5]);
		first = 0;
		}
	printf(" ]\n");

	printf("}\n}\n");
*/
	}

// Parse a single CSV line into a JSON array element
void parseCSV(int *first, char *st, float clat, float clon, float radius) {
	char csv[30][100], orig[255], stx[20];
	float rlat, rlon, rdist, mindist;
	int i, first2=1, keep=0;
	int yy, mm, dd, hh, nn;
	int ff, fat, inj;
	float pwid, plen, area;

	// Split CSV into char array
	strcpy(orig,st);
	// printf("%s", orig);
	strcpy(csv[0], strtok(st, ","));
	for(i=1;i<28;i++) {
		strcpy(csv[i], strtok(NULL, ","));	
		}

	// Year
	yy = atoi(csv[1]);

	// Month
	mm = atoi(csv[2]);

	// Day
	dd = atoi(csv[3]);

	// Hour
	strncpy(stx, csv[5], 2); stx[2] = 0;
	hh = atoi(stx);

	// Minute
	strncpy(stx, csv[5]+3, 2); stx[2] = 0;
	nn = atoi(stx);

	// F-scale
	strcpy(stx, csv[10]);
	ff = atoi(stx);
	if ((ff < 0) || (ff > 5)) ff=6;

	// Injuries
	strcpy(stx, csv[11]);
	inj = atoi(stx);

	// Fatalities
	strcpy(stx, csv[12]);
	fat = atoi(stx);

	// Path Length
	strcpy(stx, csv[19]);
	plen = atof(stx);

	// Path Width
	strcpy(stx, csv[20]);
	pwid = (atof(stx) / 1760);

	area = (plen * pwid) * 2.589F;	// Convert to sq km
	// area = (plen * pwid);	// Convert to sq km

	rlat = atof(csv[15]);	
	rlon = atof(csv[16]);	
	rdist = distance(clat, clon, rlat, rlon);
	if (rdist < radius) keep=1;
	mindist = rdist;

	rlat = atof(csv[17]);	
	rlon = atof(csv[18]);	
	rdist = distance(clat, clon, rlat, rlon);
	if (rdist < radius) keep=1;
	if (rdist < mindist) mindist = rdist;

	rlat = (atof(csv[17]) + atof(csv[15])) / 2;	
	rlon = (atof(csv[18]) + atof(csv[16])) / 2;	
	rdist = distance(clat, clon, rlat, rlon);
	if (rdist < radius) keep=1;
	if (rdist < mindist) mindist = rdist;

	if (keep==0) return;

	// Write array into JSON array element
	if (*first == 0) { printf(",\n"); } else { printf("\n"); }
	printf("   [ %3.0f, %4d, %2d, %2d, %2d, %2d, \"%s\", ", mindist, yy, mm, dd, hh, nn, csv[7]);
	for(i=10;i<27;i++) {
		if (first2 == 0) printf(", ");
		// printf("%d", i);
		printf("\"%s\"", csv[i]);
		first2 = 0;
		}
	printf(" ]");
	*first = 0;

	// Add tornado to stats arrays
	torCountByFscale[ff][0]++;
	if (inj > 0) torCountByFscale[ff][1] += inj;
	if (fat > 0) torCountByFscale[ff][2] += fat;
	if (plen > 0) torCountByFscale[ff][3] += area;
	if (mm > 0) {
		torCountByMonth[mm][0]++;
		if ((ff > 1) && (ff < 6)) torCountByMonth[mm][1]++;
		if ((ff > 3) && (ff < 6)) torCountByMonth[mm][2]++;
		if (plen > 0) torCountByMonth[mm][3] += area;
		if (inj > 0) torCountByMonth[mm][4] += inj;
		if (fat > 0) torCountByMonth[mm][5] += fat;
		if ((ff > 1) && (ff < 6)) torCountByMonth[mm][6] += area;
		}
	if (hh > -1) {
		torCountByHour[hh][0]++;
		if ((ff > 1) && (ff < 6)) torCountByHour[hh][1]++;
		if ((ff > 3) && (ff < 6)) torCountByHour[hh][2]++;
		if (plen > 0) torCountByHour[hh][3] += area;
		if (inj > 0) torCountByHour[hh][4] += inj;
		if (fat > 0) torCountByHour[hh][5] += fat;
		if ((ff > 1) && (ff < 6)) torCountByHour[hh][6] += area;
		}

	// Count the number of days affected by various tornado types
	// Assumes that tornadoes are listed in chrono order
	char today[9];
	sprintf(today, "%s%s%s", csv[1], csv[2], csv[3]);
	tda = atoi(today);
		if (allTornadoLast != tda) { 
			allTornadoDays++; 
			allTornadoLast = tda;
			}
		if ((ff > 1) && (ff < 6) && (strongTornadoLast != tda)) {
			strongTornadoDays++;
			strongTornadoLast = tda;
			}
		if ((ff > 3) && (ff < 6) && (vioTornadoLast != tda)) {
			vioTornadoDays++;
			vioTornadoLast = tda;
			}
		if ((fat > 0) && (killerTornadoLast != tda)) {
			killerTornadoDays++;
			killerTornadoLast = tda;
			}
	yda = tda;
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
