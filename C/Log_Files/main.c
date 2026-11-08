#include <stdio.h>
#include <string.h>
#include <stdlib.h>

int main(int argc, char *argv[]) {
    if (argc < 3) {
        printf("Usage: %s <log_file> <keyword>\n", argv[0]);
        return 1;
    }

    FILE *file = fopen(argv[1], "r");
    if (file == NULL) {
        perror("Error opening file");
        return 1;
    }

    char line[512];
    int line_number = 0;
    int alert_count = 0;

    while (fgets(line, sizeof(line), file) != NULL) {
        line_number++;
        if (strstr(line, argv[2]) != NULL) {
            alert_count++;
            printf("[Alert #%d] [Line %d]: %s", alert_count, line_number, line);
        }
    }

    printf("\nTotal alerts found: %d\n", alert_count);
    fclose(file);
    return 0;
}