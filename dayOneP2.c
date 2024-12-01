#include "lib.c"
#include <stdio.h>

typedef struct LLNode {
    int key;
    int val;
    struct LLNode* next;
} LLNode;


LLNode*
search(LLNode* root, int key)
{
    if (!root)
        return NULL;

    if (root->key == key)
        return root;

    LLNode* found = search(root->next, key);
    if (found) {
        return found;
    }

    return NULL;
}


void
increment(LLNode* root, int key)
{
    LLNode* node = search(root, key);

    if (!node) {
        node = (LLNode*)malloc(sizeof(LLNode));
        node->key = key;
        node->val =  0;

        LLNode* last = root;
        while (last->next) {
            last = last->next;
        }

        last->next = node;
    }

    node->val++;
    node = NULL;
}

void
free_LL(LLNode* root)
{
    if (!root)
        return;
    free_LL(root->next);
    free(root);
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

    LLNode* second_list = (LLNode*)malloc(sizeof(LLNode));

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

        increment(second_list, num);
    }

    int sum = 0;

    for (int i = 0; i < first_list_size; i++) {
        LLNode* node = search(second_list, first_list[i]);
        int nums = node ? node->val : 0;
        sum += first_list[i] * nums;
    }

    free_LL(second_list);
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
