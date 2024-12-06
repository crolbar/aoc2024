#include "lib.c"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int
get_num(char* rules, size_t rules_len, int* i)
{
    int num = 0;

    while (rules[*i] != '|' && rules[*i] != '\n' && rules[*i] != ',') {
        if (*i >= rules_len)
            break;

        num *= 10;
        num += rules[*i] - '0';

        *i = *i + 1;
    }

    return num;
}

int
main()
{
    char* input = get_input_day("five");

    char* updates = strstr(input, "\n\n");
    updates += 2; // remove the two new lines

    size_t rules_len = strlen(input) - (strlen(updates) + 2);
    char* rules = (char*)malloc(rules_len * sizeof(char));
    strncpy(rules, input, rules_len);

    int rules_map[100][100] = { 0 };

    for (int i = 0; i < rules_len; i++) {
        int num1 = get_num(rules, rules_len, &i);
        // skip separator
        i++;
        int num2 = get_num(rules, rules_len, &i);
        rules_map[num1][num2] = 1;
    }


    int res = 0;

    for (int i = 0; i < strlen(updates); i++) {
        size_t prev_nums_size = 0;
        int* prev_nums = (int*)malloc(prev_nums_size * sizeof(int));

        int correct = 1;

        while (i < strlen(updates) && updates[i] != '\n') {
            int num = get_num(updates, strlen(updates), &i);

            for (int j = 0; j < prev_nums_size; j++)
                if (rules_map[num][prev_nums[j]]) {
                    correct = 0;
                    break;
                }


            prev_nums_size++;
            prev_nums = realloc(prev_nums, prev_nums_size * sizeof(int));

            prev_nums[prev_nums_size - 1] = num;

            // skip separator
            if (updates[i] == ',')
                i++;
        }

        if (correct)
            res += prev_nums[prev_nums_size / 2];

        free(prev_nums);
    }

    printf("res: %d\n", res);

    free(rules);
    return 0;
}
