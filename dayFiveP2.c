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

int**
get_incorrect(char* input,
              size_t* incorrect_size,
              char* updates,
              int rules_map[100][100])
{

    int** incorrect = (int**)malloc(*incorrect_size * sizeof(int));

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

        if (correct) {
            free(prev_nums);
            continue;
        }

        // terminator
        prev_nums_size++;
        prev_nums = realloc(prev_nums, prev_nums_size * sizeof(int));
        prev_nums[prev_nums_size - 1] = -1;

        *incorrect_size = *incorrect_size + 1;
        incorrect =
          realloc(incorrect, *incorrect_size * (prev_nums_size * sizeof(int)));
        incorrect[*incorrect_size - 1] = prev_nums;
        prev_nums = NULL;
    }

    return incorrect;
}

void
r(int graph[100][100],
  int** visited,
  int curr,
  int** correct,
  size_t* correct_size)
{
    if ((*visited)[curr])
        return;

    (*visited)[curr] = 1;

    for (int i = 0; i < 100; i++) {
        if (graph[curr][i])
            r(graph, visited, i, correct, correct_size);
    }

    *correct_size = *correct_size + 1;
    *correct = realloc(*correct, *correct_size * sizeof(int));
    (*correct)[*correct_size - 1] = curr;
}

int
get_correct_mid_val(int graph[100][100])
{
    int* visited = (int*)malloc(100 * sizeof(int));
    for (int i = 0; i < 100; i++)
        visited[i] = 0;

    size_t correct_size = 0;
    int* correct = (int*)malloc(correct_size * sizeof(int));

    for (int i = 0; i < 100; i++) {
        for (int j = 0; j < 100; j++) {
            if (graph[i][j]) {
                r(graph, &visited, i, &correct, &correct_size);
                break;
            }
        }
    }

    // printf("correct\n");
    // for (int i = 0; i < correct_size; i++)
    //     printf("%d, ", correct[i]);
    // printf("\n");

    int res = correct[correct_size / 2];
    free(correct);
    free(visited);
    return res;
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

    size_t incorrect_size = 0;
    int** incorrect = get_incorrect(input, &incorrect_size, updates, rules_map);

    // for (int i = 0; i < incorrect_size; i++) {
    //     for (int j = 0; incorrect[i][j] != -1; j++)
    //         printf("%d, ", incorrect[i][j]);
    //     printf("\n");
    // }

    int res = 0;

    for (int i = 0; i < incorrect_size; i++) {
        int graph[100][100] = { 0 };

        int j = 0;
        for (int j = 0; incorrect[i][j] != -1; j++) {
            int num = incorrect[i][j];

            for (int j2 = 0; incorrect[i][j2] != -1; j2++) {
                if (rules_map[num][incorrect[i][j2]])
                    graph[num][incorrect[i][j2]] = 1;
            }

            // printf("%d, ", num);
        }
        // printf("\n");

        // for (int i = 0; i < 100; i++) {
        //     for (int j = 0; j < 100; j++) {
        //         if (graph[i][j])
        //             printf("%d|%d\n", i, j);
        //     }
        // }
        // printf("\n");

        res += get_correct_mid_val(graph);
        // printf("\n");
    }

    printf("res: %d\n", res);

    for (int i = 0; i < incorrect_size; i++)
        free(incorrect[i]);
    free(incorrect);
    free(input);
    free(rules);
    return 0;
}
