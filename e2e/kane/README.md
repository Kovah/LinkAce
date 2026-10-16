# End-to-end (e2e) tests for web

Browser end-to-end tests for LinkAce, authored with [KaneCLI](https://www.testmuai.com/kane-cli/) by TestMu AI. Each test is a plain-English `_test.md` file; KaneCLI runs it in a real Chrome browser and records each step so later runs replay without re-authoring.

| Test | What it covers |
|---|---|
| `setup_test.md` | The web setup with SQLite: requirements check, database configuration, and creating the user account, ending on the dashboard |
| `link_test.md` | Logging in, adding a link with a new tag, and checking it on the link page, in the Links list and in the Tags list |

They complement the PHPUnit suite in `tests/` by going through the whole app in a browser. The Docker image only copies the app folders, so nothing here ends up in it.

## Run the tests

The tests expect a new LinkAce install at `http://localhost:8096` using SQLite, for example with the official image:

```bash
docker run -d --name linkace-e2e -p 8096:80 \
  -e APP_KEY="base64:$(openssl rand -base64 32)" -e APP_URL=http://localhost:8096 \
  -e DB_CONNECTION=sqlite -e DB_DATABASE=/app/database/database.sqlite \
  -e DB_USERNAME=linkace \
  linkace/linkace:latest
```

`DB_USERNAME` is not used by SQLite, but without it the "Configure Database" step currently fails with "The db user format is invalid.", because the hidden MySQL user field is submitted empty.

Run `setup_test.md` first: it creates the account (`alexmorgan`, `alex@example.com` / `LinkAce-Test-2026`) that `link_test.md` logs in with. To run them again, recreate the container.

### With KaneCLI

```bash
npm install -g @testmuai/kane-cli
kane-cli login
kane-cli testmd run e2e/kane/setup_test.md --agent
kane-cli testmd run e2e/kane/link_test.md --agent
```

### With the exported Playwright code

Each test also has an exported Playwright (JavaScript) version under `output-<test>/playwright-javascript-code/`:

```bash
cd e2e/kane/output-link/playwright-javascript-code
npm install
npx playwright install chromium
node test.js
```

Copy `.env.example` to `.env` and fill in your TestMu AI (LambdaTest) credentials first.

## Editing a test

Edit the step text in the `_test.md` file and run it again with `kane-cli testmd run`. Changed steps (and the steps after them) are re-recorded; unchanged steps replay from `output-<test>/`.
