#include <stdio.h>
#include <stdlib.h>
#include <string.h>

char*
get_input_day(char* day)
{
    char* fmt = "day_%s_input";
    char* name = malloc(strlen(day) + strlen(fmt));
    sprintf(name, fmt, day);

    FILE* f = fopen(name, "r");

    if (!f)
        return "err reading file";

    fseek (f, 0, SEEK_END);
    long length = ftell (f);
    fseek (f, 0, SEEK_SET);

    char* buf = malloc(length);
    fread(buf, 1, length, f);

    free(name);
    return buf;
}
