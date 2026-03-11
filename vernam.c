#include <stdio.h>
#include <ctype.h>
#include <stdlib.h>
#include <time.h>

#define MAX_MESSAGE 100
#define MIN_ASCII 65
#define MAX_ASCII 90

int input(char[], int);
void gen_key(char[], int);
int to_bin(int);
char to_char(int);
void printb(int);

int main() {
    char message[MAX_MESSAGE];
    
    printf("Mensaje: ");
    int message_len = input(message, MAX_MESSAGE);

    char key[message_len];
    gen_key(key, message_len);
   // char key[] = "XTRF";

    int cypher[message_len];
    for (int i = 0; i < message_len; i++) {
        cypher[i] = to_bin(message[i]) ^ to_bin(key[i]);        
    }

    printf("Key: %s\n", key);
    printf("Cypher: ");
    for (int i = 0; i < message_len; i++) {
        printb(cypher[i]);
        printf(" ");
    }

    char decypher[message_len];
    for (int i = 0; i < message_len; i++) {
        decypher[i] = to_char(cypher[i] ^ to_bin(key[i]));
    }

    printf("\nDecypher: %s\n", decypher);
}

int input(char s[], int limit) {
    int c, i;
    
    for (i = 0; i < limit - 1 && (c = getchar()) != '\n'; i++) {
        if (c != ' ') {
            s[i] = toupper(c);
        } else {
            i--;
        }
    }
    
    s[i] = '\0';
    
    return i;
}

void gen_key(char s[], int n) {
    int r = 0;

    srand(time(NULL));

    for (int i = 0; i < n; i++) {
        s[i] = (rand() % (MAX_ASCII - MIN_ASCII + 1)) + MIN_ASCII;
    }
}

int to_bin(int c) {
    int a = c / 10, b = c % 10;

    return (a << 4) | b;
}

char to_char(int x) {
    int a = x >> 4, b = x & 15;

    return (a * 10) + b;
}

void printb(int x) {
    for (int i = 7; i >= 0; i--) {
        printf("%d", (x & (1 << i)) ? 1 : 0);
    }
}