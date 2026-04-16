a, m, x, n, i = 0, 0, [], 0, 1

a = int(input('a = '))
m = int(input('m = '))
n = int(input('n = '))


while len(x) != n:
    if (a * i) % m == 1: x.append(i)
    i += 1

print(x)
