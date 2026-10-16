import testmu, { resolveVar, setVar } from '@testmuai/playwright-bindings';
import { expect } from '@playwright/test';

testmu.configure({
  "build": "2bb47c52-714e-418b-aad3-b36c97eafc9c",
  "name": "Add and Verify a Tagged Link",
  "tcId": "TC-213",
  "network": (process.env.NETWORK ?? "false").toLowerCase() === "true",
  "autoHealVersion": "AH2",
  "defaultActionTimeoutMs": 10000,
  "defaultNavigationTimeoutMs": 60000,
  "kaneRunV4": true
});

testmu.test("Add and Verify a Tagged Link", async (page) => {
  let element_0, element_1, element_6, element_2, element_3, element_4, element_10, element_5, element_12, element_13;
  await testmu.step("Navigate to http://localhost:8096/login", { instructionId: "1f6f29c2-ee76-4950-9cf5-a3a0440f1b07" }, async () => {
    await page.goto("http://localhost:8096/login");
  });
  
  await testmu.step("Waiting for the queued plan steps to run", { instructionId: "ad8712d5-5460-4b5c-b864-dcc3b7a0c00d" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Type \"alex@example.com\" into \"Email\"", { instructionId: "c4bf6b9d-f3dd-4614-bef9-e268efe43247" }, async () => {
    element_0 = page.locator("internal:role=textbox[name=\"Email\"i]");
    await element_0.click();
    await element_0.fill("alex@example.com");
  });
  
  await testmu.step("Type \"LinkAce-Test-2026\" into \"Password\"", { instructionId: "154406fa-35a4-4f58-a615-a5b9652fb4d7" }, async () => {
    element_1 = page.locator("internal:role=textbox[name=\"Password\"i]");
    await element_1.click();
    await element_1.fill("LinkAce-Test-2026");
  });
  
  await testmu.step("Clicking the Login button", { instructionId: "56361ab9-4ca2-4e7c-8234-6ed7cbb13537" }, async () => {
    element_6 = page.locator("internal:role=button[name=\"Login\"i]");
    await element_6.click();
  });
  
  await testmu.step("Waiting for the dashboard to finish loading", { instructionId: "53286dbd-662c-4908-b5c7-6ac8ca928165" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Checking whether the dashboard content is present", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("dashboard_shown", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {24: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(24) !== null);\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=main"], query: "whether the dashboard main content is present", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The dashboard is shown." }));
  });
  
  await testmu.step("Reading the dashboard greeting", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("dashboard_greeting", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {25: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(25).textContent);\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=heading[name=\"Hello alexmorgan!\"i]"], query: "the dashboard greeting text", expectedValue: "Hello alexmorgan!", needsUnitConversion: false, operator: "equals", transforms: ["strip"], condition: "The dashboard displays \"Hello alexmorgan!\"." }));
  });
  
  await testmu.step("Assertion check", { instructionId: "2e621902-0b90-41c6-99bd-e074f5609662" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The dashboard is shown.", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{dashboard_shown}}", "store_key": "dashboard_shown", "variable_refs": {"{{dashboard_shown}}": "true"}}, {"description": "The dashboard displays \"Hello alexmorgan!\".", "passed": true, "operator": "equals", "transforms": ["strip"], "json_path": null, "expected": "Hello alexmorgan!", "extracted_value": "{{dashboard_greeting}}", "store_key": "dashboard_greeting", "variable_refs": {"{{dashboard_greeting}}": "Hello alexmorgan!"}}], "sub_checks": [{"description": "The dashboard is shown.", "store_key": "dashboard_shown", "expected_value": "true", "extracted_value": "{{dashboard_shown}}", "operator": "equals", "transforms": []}, {"description": "The dashboard displays \"Hello alexmorgan!\".", "store_key": "dashboard_greeting", "expected_value": "Hello alexmorgan!", "extracted_value": "{{dashboard_greeting}}", "operator": "equals", "transforms": ["strip"]}], "composite_operator": "and", "claim": "the dashboard is shown with \"Hello alexmorgan!\""});
  });
  
  await testmu.step("Navigate to http://localhost:8096/links/create", { instructionId: "6375045f-c8bb-4c53-8a3c-80d748b6c50b" }, async () => {
    await page.goto("http://localhost:8096/links/create");
  });
  
  await testmu.step("Waiting for the plan to load and accept the input steps", { instructionId: "e934f9fb-a374-4e39-9a6b-1c39d666fbd6" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Type \"https://example.com\" into \"URL\"", { instructionId: "128720da-8953-4f1f-aa2a-912bb7d6488c" }, async () => {
    element_2 = page.locator("internal:role=textbox[name=\"URL\"i]");
    await element_2.click();
    await element_2.fill("https://example.com");
  });
  
  await testmu.step("Type \"Example Domain\" into \"Title\"", { instructionId: "2ffb6595-202e-4cf4-b001-e42d577c6fb6" }, async () => {
    element_3 = page.locator("internal:role=textbox[name=\"Title\"i]");
    await element_3.click();
    await element_3.fill("Example Domain");
  });
  
  await testmu.step("Typing into tags input", { instructionId: "ab1631cb-58ce-401b-a0a6-e4353fab7b0e" }, async () => {
    element_4 = page.locator("internal:role=textbox[name=\"Select some Tags\"i]");
    await element_4.click();
    await element_4.fill("reference");
  });
  
  await testmu.step("Waiting for the autocomplete option to load", { instructionId: "50a88fa0-da56-448d-9a42-70d4fca03dd8" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Clicking the Tags combobox in the Add Link form", { instructionId: "42093d19-189f-46f8-8230-46beb12b7b1f" }, async () => {
    element_10 = page.locator("internal:role=combobox[name=\"Tags\"i]");
    await element_10.click();
  });
  
  await testmu.step("Typing reference into Tags autocomplete combobox", { instructionId: "b8bd5049-edbc-4b32-9496-88dd79771ec8" }, async () => {
    element_5 = page.locator("internal:role=combobox[name=\"Tags\"i]");
    await element_5.click();
    await element_5.fill("reference");
  });
  
  await testmu.step("Clicking Add reference option in the tags autocomplete dropdown", { instructionId: "cca37c40-1c62-4ea9-816f-924f7bf1c2f3" }, async () => {
    element_12 = page.locator("internal:text=\"Add reference\u2026\"i");
    await element_12.click();
  });
  
  await testmu.step("Checking the selected reference tag in the Tags field", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("reference_selected_tag", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {41: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(41).textContent.trim() === 'reference');\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["div >> internal:has-text=/^reference$/ >> nth=1"], query: "whether the reference tag is shown as selected in the Tags field", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "\"reference\" is shown as a selected tag in the \"Tags\" field." }));
  });
  
  await testmu.step("Assertion check", { instructionId: "dc69faed-e313-459f-a4d3-76ad958875ee" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "\"reference\" is shown as a selected tag in the \"Tags\" field.", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{reference_selected_tag}}", "store_key": "reference_selected_tag", "variable_refs": {"{{reference_selected_tag}}": "true"}}], "sub_checks": [{"description": "\"reference\" is shown as a selected tag in the \"Tags\" field.", "store_key": "reference_selected_tag", "expected_value": "true", "extracted_value": "{{reference_selected_tag}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "\"reference\" is shown as a selected tag in the \"Tags\" field."});
  });
  
  await testmu.step("Clicking Add Link button in the bottom-right of the form", { instructionId: "f5db2b09-b02f-4a02-85b7-ffeb645422a4" }, async () => {
    element_13 = page.locator("internal:role=button[name=\"Add Link\"i]");
    await element_13.click();
  });
  
  await testmu.step("Waiting for the added-link detail page to finish loading", { instructionId: "dd144b85-b9ae-4033-8f16-980b446569e8" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Checking the link-added success alert", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("link_added_successfully", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {25: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(25).textContent.trim() === 'Link added successfully.');\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:text=\"Link added successfully.\"i"], query: "whether the link-added success alert is present", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The page shows \"Link added successfully.\"" }));
  });
  
  await testmu.step("Checking the Example Domain link and URL", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("example_domain_link", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {27: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(27).textContent.trim() === 'Example Domain' && el(27).href === 'https://example.com/');\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=link[name=\"Example Domain\"i]"], query: "whether the Example Domain link has the example.com URL", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The page shows the link \"Example Domain\" with \"https://example.com\"." }));
  });
  
  await testmu.step("Checking the reference tag in the Tags section", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("link_page_reference_tag", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {62: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(62).textContent.includes('reference'));\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=link[name=\"alexmorgan/reference\"i]"], query: "whether the Tags section contains a tag with reference", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The \"Tags\" section of the link page shows a tag containing \"reference\"." }));
  });
  
  await testmu.step("Assertion check", { instructionId: "976cc00f-e4bf-4120-b89e-150d8853cf2e" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The page shows \"Link added successfully.\"", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{link_added_successfully}}", "store_key": "link_added_successfully", "variable_refs": {"{{link_added_successfully}}": "true"}}], "sub_checks": [{"description": "The page shows \"Link added successfully.\"", "store_key": "link_added_successfully", "expected_value": "true", "extracted_value": "{{link_added_successfully}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "The page shows \"Link added successfully.\""});
  });
  
  await testmu.step("Assertion check", { instructionId: "b653a378-56f9-4057-949f-3c6e9fe886cf" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The page shows the link \"Example Domain\" with \"https://example.com\".", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{example_domain_link}}", "store_key": "example_domain_link", "variable_refs": {"{{example_domain_link}}": "true"}}], "sub_checks": [{"description": "The page shows the link \"Example Domain\" with \"https://example.com\".", "store_key": "example_domain_link", "expected_value": "true", "extracted_value": "{{example_domain_link}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "The page shows the link \"Example Domain\" with \"https://example.com\"."});
  });
  
  await testmu.step("Assertion check", { instructionId: "6b127de6-6605-4b4f-a4bd-cf2e028dd9a4" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The \"Tags\" section of the link page shows a tag containing \"reference\".", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{link_page_reference_tag}}", "store_key": "link_page_reference_tag", "variable_refs": {"{{link_page_reference_tag}}": "true"}}], "sub_checks": [{"description": "The \"Tags\" section of the link page shows a tag containing \"reference\".", "store_key": "link_page_reference_tag", "expected_value": "true", "extracted_value": "{{link_page_reference_tag}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "The \"Tags\" section of the link page shows a tag containing \"reference\"."});
  });
  
  await testmu.step("Navigate to http://localhost:8096/links", { instructionId: "3987ca24-b160-405f-a4b4-1533c895bcc7" }, async () => {
    await page.goto("http://localhost:8096/links");
  });
  
  await testmu.step("Reading the link title from the links list", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("links_list_example_domain_check", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {42: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(42).textContent);\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=link[name=\"Example Domain\"i]"], query: "the link title shown for the Example Domain item in the links list", expectedValue: "Example Domain", needsUnitConversion: false, operator: "equals", transforms: ["strip"], condition: "the links list shows \"Example Domain\"" }));
  });
  
  await testmu.step("Assertion check", { instructionId: "0968b710-429e-42eb-8fdc-09fcdaf45acb" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "the links list shows \"Example Domain\"", "passed": true, "operator": "equals", "transforms": ["strip"], "json_path": null, "expected": "Example Domain", "extracted_value": "{{links_list_example_domain_check}}", "store_key": "links_list_example_domain_check", "variable_refs": {"{{links_list_example_domain_check}}": "Example Domain"}}], "sub_checks": [{"description": "the links list shows \"Example Domain\"", "store_key": "links_list_example_domain_check", "expected_value": "Example Domain", "extracted_value": "{{links_list_example_domain_check}}", "operator": "equals", "transforms": ["strip"]}], "composite_operator": "and", "claim": "the links list shows \"Example Domain\""});
  });
  
  await testmu.step("Navigating to http://localhost:8096/tags", { instructionId: "1f310110-2186-43e5-880e-5dcde56ecace" }, async () => {
    await page.goto("http://localhost:8096/tags");
  });
  
  await testmu.step("Waiting for the Tags page to finish loading", { instructionId: "f0593a21-b639-4caa-beb4-8ec18b45cc9b" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Checking the tags list for a reference tag", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("tags_list_reference_check", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {50: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(50).textContent);\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=link[name=\"alexmorgan/reference\"i]"], query: "whether a tag in the tags list contains the text reference", expectedValue: "reference", needsUnitConversion: false, operator: "contains", transforms: ["strip", "lowercase"], condition: "the tags list shows a tag containing \"reference\"" }));
  });
  
  await testmu.step("Assertion check", { instructionId: "031709c7-2638-4ee1-b161-34d2d7837c62" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "the tags list shows a tag containing \"reference\"", "passed": true, "operator": "contains", "transforms": ["strip", "lowercase"], "json_path": null, "expected": "reference", "extracted_value": "{{tags_list_reference_check}}", "store_key": "tags_list_reference_check", "variable_refs": {"{{tags_list_reference_check}}": "\n            \n    alexmorgan/reference\n\n        "}}], "sub_checks": [{"description": "the tags list shows a tag containing \"reference\"", "store_key": "tags_list_reference_check", "expected_value": "reference", "extracted_value": "{{tags_list_reference_check}}", "operator": "contains", "transforms": ["strip", "lowercase"]}], "composite_operator": "and", "claim": "the tags list shows a tag containing \"reference\""});
  });
});

testmu.run();