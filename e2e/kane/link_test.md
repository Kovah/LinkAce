---
mode: testing
url: http://localhost:8096/login
max_steps: 40
---

# Save a tagged link and find it

## Log in
Open http://localhost:8096/login.
Enter "alex@example.com" as the email and "LinkAce-Test-2026" as the password, and click "Login".
Verify the dashboard is shown with "Hello alexmorgan!", waiting for it to load if needed.

## Add a link
Open http://localhost:8096/links/create.
Enter "https://example.com" as the URL and "Example Domain" as the title.
In the "Tags" field, type "reference", wait for the "Add reference…" option to appear below the field, and click that option. Do not press Enter.
Verify "reference" is shown as a selected tag in the "Tags" field.
Click the "Add Link" button.
Verify the page shows "Link added successfully." and the link "Example Domain" with "https://example.com", waiting for it to load if needed.
Verify the "Tags" section of the link page shows a tag containing "reference".

## Find the link
Open http://localhost:8096/links.
Verify the links list shows "Example Domain", waiting for it to update if needed.
Open http://localhost:8096/tags.
Verify the tags list shows a tag containing "reference".
