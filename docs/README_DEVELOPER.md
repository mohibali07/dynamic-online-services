# Developer Documentation Hub

Welcome to the `dynamic-online-services` developer documentation. This guide serves as the entry point for all technical contributors.

## 📂 Documentation Index

- **[Contributing Guidelines](CONTRIBUTING.md)**: Standards for code style, git flow, and pull requests.
- **[Docker Setup](DOCKER.md)**: Instructions for setting up the local development environment using Docker.
- **[Hooks Reference](HOOKS.md)**: Comprehensive documentation of all actions and filters available in the plugin.
- **[Plugin Architecture](plugin_purpose.md)**: Overview of the plugin's core purpose and high-level design.

## 🤖 AI-Assisted Development (2025 Standard)

This repository is configured for **Vibe Coding** with strict architectural guardrails.

### The System Prompt (`.cursorrules`)

The root directory contains a `.cursorrules` file. This is the **Source of Truth** for all AI agents (Cursor, Antigravity, etc.). It enforces:

1. **Repository Hygiene**: No artifacts or media integrity.
2. **SOLID Architecture**: SRP, OCP, and strict typing.
3. **Security**: Zero-trust approach to secrets and dependencies.

### Workflow

- **Plan**: Always start with an implementation plan.
- **Rule Check**: Consult `.cursorrules` before generating code.
- **Verify**: Run `composer test` and `composer lint` to validate changes.

## 🛠 Command Reference

| Command | Description |
|Str |Str |
| `composer lint` | Check code style against WordPress standards. |
| `composer lint:fix` | Automatically fix code style issues. |
| `composer test` | Run the PHPUnit test suite. |
| `composer test:coverage` | Generate code coverage report. |
