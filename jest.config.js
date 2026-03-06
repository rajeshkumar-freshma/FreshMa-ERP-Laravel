/** @type {import('jest').Config} */
module.exports = {
    testEnvironment: 'jsdom',
    roots: ['<rootDir>/tests/Frontend'],
    testMatch: ['**/*.test.js'],
    verbose: true,
};
