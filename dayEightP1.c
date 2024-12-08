#include "lib.c"
#include <stdio.h>
#include <stdlib.h>

int
get_line_size(char* str)
{
    int n = 0;

    for (int i = 0; i < strlen(str); i++) {
        if (str[i] == '\n')
            break;
        n++;
    }

    return n;
}

int
get_num_lines(char* str)
{
    int n = 0;

    for (int i = 0; i < strlen(str); i++) {
        if (str[i] == '\n')
            n++;
    }

    return n;
}

char**
get_lines(char* input, int num_lines, int line_len)
{

    char** lines = (char**)malloc(num_lines * sizeof(char*));

    char* curr_line_start = input;

    for (int i = 0; i < num_lines; i++) {
        lines[i] = (char*)malloc(line_len * sizeof(char));

        strncpy(lines[i], curr_line_start, line_len);
        curr_line_start = strchr(curr_line_start, '\n');

        curr_line_start++; // skip the whitespace
    }

    return lines;
}

typedef struct Antenna
{
    int r;
    int c;
    char freq;
} Antenna;

int min(int a, int b) {
    return (a < b) ? a : b;
}

int max(int a, int b) {
    return (a > b) ? a : b;
}


int
main()
{
    char* input = get_input_day("eight");
    // printf("%s\n", input);

    int line_size = get_line_size(input);
    int num_lines = get_num_lines(input);
    char** lines = get_lines(input, num_lines, line_size);

    size_t antenas_size = 0;
    Antenna* antenas = (Antenna*)malloc(antenas_size * sizeof(Antenna));

    for (int r = 0; r < num_lines; r++) {
        for (int c = 0; c < line_size; c++) {
            if (lines[r][c] == '.')
                continue;
            Antenna a = (Antenna){ .c = c, .r = r, .freq = lines[r][c] };

            antenas_size++;
            antenas = realloc(antenas, antenas_size * sizeof(Antenna));
            antenas[antenas_size - 1] = a;
        }
    }

    for (int i = 0; i < antenas_size; i++) {
        Antenna a1 = antenas[i];
        for (int j = 0; j < antenas_size; j++) {
            Antenna a2 = antenas[j];

            if (a1.r == a2.r && a1.c == a2.c)
                continue;

            if (a1.freq != a2.freq)
                continue;

            int row_diff = abs(a1.r - a2.r);
            int col_diff = abs(a1.c - a2.c);

            int is_top_left =
                (
                 (a1.r < a2.r && a1.c < a2.c)
                 || (a1.r > a2.r && a1.c > a2.c)
                 ) ? 1 : 0;

            int taR = min(a1.r, a2.r) - row_diff;
            int taC = ((a1.r < a2.r) ? a1.c : a2.c) + (((is_top_left) ? -1 : 1) * col_diff);
            int baR = max(a1.r, a2.r) + row_diff;
            int baC = ((a1.r > a2.r) ? a1.c : a2.c) + (((is_top_left) ? 1 : -1) * col_diff);

            if (taR >= 0 && taR < num_lines
                && taC >= 0 && taC < line_size)
                lines[taR][taC] = '#';

            if (baR >= 0 && baR < num_lines
                && baC >= 0 && baC < line_size)
                lines[baR][baC] = '#';
        }
    }
        
    int res = 0;
    for (int r = 0; r < num_lines; r++)
        // printf("%s\n", lines[r]);
        for (int c = 0; c < num_lines; c++)
        if (lines[r][c] == '#')
            res++;

    printf("%d\n", res);


    for (int i = 0; i < num_lines; i++)
        free(lines[i]);
    free(lines);
    free(input);
    return 0;
}
