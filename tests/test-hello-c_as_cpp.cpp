/*
 * This should give CORRECT on the default problem 'hello'.
 * This C code is valid C++ too, and has the right extension.
 *
 * @EXPECTED_RESULTS@: CORRECT
 */

#include <stdio.h>

int main(int argc, char **argv)
{
	int i;
	char hello[20] = "Hello world!";
	printf("%s\n",hello);
	return 0;
}
