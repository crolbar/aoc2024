#include "lib.c"
#include <stdbool.h>
#include <stdio.h>
#include <string.h>

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
gen_grid(char* input, int line_len, int num_lines)
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

// root is the head
typedef struct Queue
{
    char c;
    int x;
    int y;
    int* dir;

    struct Queue* next;

    bool is_root;
    struct Queue* tail;
} Queue;

Queue
q_new()
{
    Queue q = {
        .c = 'r',
        .y = 0,
        .x = 0,
        .dir = NULL,
        .next = NULL,
        .is_root = true,
        .tail = NULL,
    };

    return q;
}

// the passed poiter to Queue has to be the root node
void
q_enqueue(Queue* q, char c, int x, int y, int* dir)
{
    if (!q->is_root)
        return;

    Queue* node = (Queue*)malloc(sizeof(Queue));

    node->c = c;
    node->y = y;
    node->x = x;
    node->dir = dir;
    node->next = NULL;

    // useless
    node->is_root = false;
    node->tail = NULL;

    // enqueueing on an empty queue
    if (!q->tail) {
        q->next = node;
        q->tail = node;
        return;
    }

    q->tail->next = node;
    q->tail = node;
}

Queue*
q_dequeue(Queue* q)
{
    if (!q->is_root)
        return NULL;

    Queue* node = q->next;

    if (!node)
        return NULL;

    if (q->next->next)
        q->next = q->next->next;

    if (q->tail == node) {
        q->tail = NULL;
        q->next = NULL;
    }

    return node;
}

int dirs[8][2] = {
    { -1, -1 }, // up-left
    { -1, 1 },  // up-right
    { 1, -1 },  // down-left
    { 1, 1 },   // down-right
};

int
main()
{
    Queue q = q_new();

    char* input =
      "MMMSXXMASM\nMSAMXMSMSA\nAMXSXMAAMM\nMSAMASMSMX\nXMASAMXAMM\nXXAMMXXAMA\n"
      "SMSMSASXSS\nSAXAMASAAA\nMAMMMXMMMM\nMXMXAXMASX\n";
    // char* input = get_input_day("four");

    int line_len = get_line_size(input);
    int num_lines = get_num_lines(input);

    char** grid = gen_grid(input, line_len, num_lines);

    // for (int i = 0; i < num_lines; i++) {
    //     printf("%d: [%s]\n", i, grid[i]);
    // }


    for (int y = 0; y < num_lines; y++) {
        for (int x = 0; x < line_len; x++) {
            if (grid[y][x] == 'M')
                q_enqueue(&q, 'M', x, y, NULL);
        }
    }

    int c = 0;

    while (q.next) {
        Queue* node = q_dequeue(&q);

        if (node->c == 'S') {
            int bY = node->y + (-1 * node->dir[0]);
            int bX = node->x + (-1 * node->dir[1]);

            if (grid[bY][bX] == '#')
                c++;

            grid[bY][bX] = '#';

            continue;
        }

        char next_char = ' ';

        switch (node->c) {
            case 'M':
                next_char = 'A';
                break;
            case 'A':
                next_char = 'S';
                break;
        }

        if (next_char == ' ') {
            continue;
        }

        for (int i = 0; i < 8; i++) {
            int* dir = dirs[i];
            int nY = node->y + dir[0];
            int nX = node->x + dir[1];

            if (nY < 0 || nY >= num_lines)
                continue;
            if (nX < 0 || nX >= line_len)
                continue;

            if (node->dir && (node->dir[0] != dir[0] || node->dir[1] != dir[1]))
                continue;

            if (grid[nY][nX] == next_char)
                q_enqueue(&q, next_char, nX, nY, dir);
        }
    }

    printf("%d\n", c);

    return 0;
}
