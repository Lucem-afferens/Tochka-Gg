// Пример unit-теста для Jest
// Для запуска: npx jest src/js/index.test.js

// Пример функции (можно заменить на реальную)
function sum(a, b) {
  return a + b;
}

test('sum складывает два числа', () => {
  expect(sum(2, 3)).toBe(5);
}); 