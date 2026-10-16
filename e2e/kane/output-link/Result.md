---
test: ../link_test.md
status: passed
started: 2026-10-16T15:29:29.423Z
duration_s: 199
session_id: 94423ed1-79ad-4e62-8b93-9267f0c57dfd
---

# Save a tagged link and find it — Result

## Log in ✓ passed (44.9s)
md5: 28c318cb3503878bf180e029a6e49998
Open http://localhost:8096/login.
Enter "alex@example.com" as the email and "LinkAce-Test-2026" as the password, and click "Login".
Verify the dashboard is shown with "Hello alexmorgan!", waiting for it to load if needed.

## Add a link ✓ passed (103.9s)
md5: d69e286f1f54392406f8b9ee88f9cf0e
Open http://localhost:8096/links/create.
Enter "https://example.com" as the URL and "Example Domain" as the title.
In the "Tags" field, type "reference", wait for the "Add reference…" option to appear below the field, and click that option. Do not press Enter.
Verify "reference" is shown as a selected tag in the "Tags" field.
Click the "Add Link" button.
Verify the page shows "Link added successfully." and the link "Example Domain" with "https://example.com", waiting for it to load if needed.
Verify the "Tags" section of the link page shows a tag containing "reference".

## Find the link ✓ passed (44.4s)
md5: cd6b1961afa517ccff78e957a05a4677
Open http://localhost:8096/links.
Verify the links list shows "Example Domain", waiting for it to update if needed.
Open http://localhost:8096/tags.
Verify the tags list shows a tag containing "reference".
