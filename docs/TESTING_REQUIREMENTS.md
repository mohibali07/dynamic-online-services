# Testing Requirements & Extensions

To ensure the best experience when running tests for **Dynamic Online Services**, please ensure the following are installed.

## System Requirements

- **Node.js**: v16+
- **Composer**: v2+
- **PHP**: v7.4+ (Compatible with v8.x)

## Browser Binaries (Playwright)

Run the following command to install the necessary browser binaries for E2E testing:

```bash
npx playwright install
```

## VS Code Extensions

We recommend installing the following extensions to assist with testing and code quality:

| Extension | ID | Purpose |
| :--- | :--- | :--- |
| **Playwright Test for VSCode** | `ms-playwright.playwright` | Run and debug Playwright tests directly from VS Code. |
| **PHP Debug** | `xdebug.php-debug` | Debug PHPUnit tests. |
| **PHP Intelephense** | `bmewburn.vscode-intelephense-client` | PHP code intelligence. |
| **axe Accessibility Linter** | `deque-systems.vscode-axe-linter` | Catch accessibility issues in your code while you type. |
| **ESLint** | `dbaeumer.vscode-eslint` | JavaScript linting. |

## Running Tests

- **Unit Tests**: `npm run test` (covers PHPUnit)
- **E2E Tests**: `npm run test:e2e` (covers Playwright)
