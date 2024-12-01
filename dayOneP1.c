#include "lib.c"
#include <stdio.h>
#include <string.h>


int
pivot(int* nums, int l, int r)
{
    int p = nums[r];

    int i = l - 1;

    for (int j = l; j < r; j++) {
        if (nums[j] < p) {
            i++;
            int tmp = nums[j];
            nums[j] = nums[i];
            nums[i] = tmp;
        }
    }

    i++;
    nums[r] = nums[i];
    nums[i] = p;

    return i;
}

void
qs(int* nums, int l, int r)
{
    if (l >= r)
        return;

    int p = pivot(nums, l, r);

    qs(nums, l, p - 1);
    qs(nums, p + 1, r);
}


int
solve(char* input)
{
    size_t size = strlen(input);

    size_t num_pairs = 1; // one for the first line
    for (int i = 0; i < size; i++)
        if (input[i] == '\n') num_pairs++;

    int* first_list = malloc(num_pairs * sizeof(int));
    size_t first_list_size = 0;

    int* second_list = malloc(num_pairs * sizeof(int));
    size_t second_list_size = 0;

    for (int i = 0; i < size; i++) {
        int num = 0;

        for (;input[i] != ' '; i++) {
            num *= 10;
            num += (input[i] - '0');
        }

        first_list[first_list_size++] = num;

        // skip the three whitespaces
        i += 3;

        num = 0;
        for (;input[i] != '\n'; i++) {
            num *= 10;
            num += (input[i] - '0');
        }

        second_list[second_list_size++] = num;
    }

    qs(first_list, 0, first_list_size - 1);
    qs(second_list, 0, second_list_size - 1);

    int sum = 0;

    for (int i = 0; i < first_list_size; i++) {
        sum += abs(first_list[i] - second_list[i]);
    }

    free(first_list);
    free(second_list);

    return sum;
}

int
main()
{
    char* input = get_input_day("one");

    // printf("%s", input);
    int res = solve(input);
    printf("%d\n", res);

    free(input);
    return 0;
}
