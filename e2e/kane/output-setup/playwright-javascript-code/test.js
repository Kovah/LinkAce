import testmu, { resolveVar, setVar } from '@testmuai/playwright-bindings';
import { expect } from '@playwright/test';

testmu.configure({
  "build": "d92e34df-2b48-4b89-8d51-2d7866b05cb2",
  "name": "Complete LinkAce Initial Setup",
  "tcId": "TC-210",
  "network": (process.env.NETWORK ?? "false").toLowerCase() === "true",
  "autoHealVersion": "AH2",
  "defaultActionTimeoutMs": 10000,
  "defaultNavigationTimeoutMs": 60000,
  "kaneRunV4": true
});

testmu.test("Complete LinkAce Initial Setup", async (page) => {
  let element_4, element_5, element_6, element_0, element_1, element_2, element_3, element_11, element_12;
  await testmu.step("Navigate to http://localhost:8096/", { instructionId: "4a9662d1-d090-49bb-a79c-eb6c36d31e39" }, async () => {
    await page.goto("http://localhost:8096/");
  });
  
  await testmu.step("Reading the LinkAce setup welcome heading", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("linkace_setup_welcome_check", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {2: 0};\n  const el = (i) => els[__m[i]];\n  const __v = ([...el(2).querySelectorAll('*')].find(n => n.textContent.trim() === 'Welcome to the LinkAce setup')?.textContent.trim() ?? null);\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:text=\"Welcome to the LinkAce setup In the following steps you will set up LinkAce to\"i"], query: "the LinkAce setup welcome heading text", expectedValue: "Welcome to the LinkAce setup", needsUnitConversion: false, operator: "equals", transforms: ["strip"], condition: "The page shows \"Welcome to the LinkAce setup\"." }));
  });
  
  await testmu.step("Assertion check", { instructionId: "8a97e206-2304-4a2f-a38f-9a61266bfd71" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The page shows \"Welcome to the LinkAce setup\".", "passed": true, "operator": "equals", "transforms": ["strip"], "json_path": null, "expected": "Welcome to the LinkAce setup", "extracted_value": "{{linkace_setup_welcome_check}}", "store_key": "linkace_setup_welcome_check", "variable_refs": {"{{linkace_setup_welcome_check}}": "Welcome to the LinkAce setup"}}], "sub_checks": [{"description": "The page shows \"Welcome to the LinkAce setup\".", "store_key": "linkace_setup_welcome_check", "expected_value": "Welcome to the LinkAce setup", "extracted_value": "{{linkace_setup_welcome_check}}", "operator": "equals", "transforms": ["strip"]}], "composite_operator": "and", "claim": "the page shows \"Welcome to the LinkAce setup\""});
  });
  
  await testmu.step("Clicking Check Requirements link in the setup welcome panel", { instructionId: "ba7c6ede-d72f-4dd7-a261-b7e2a2d617ae" }, async () => {
    element_4 = page.locator("internal:role=link[name=\"Check Requirements\"i]");
    await element_4.click();
  });
  
  await testmu.step("Waiting for Check Requirements page to finish loading", { instructionId: "c07f7c01-ada1-44f2-8b18-efb9ece3b7a7" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Checking whether the Check Requirements page is shown", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("check_requirements_page_shown", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {2: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(2).querySelector('.card-header')?.textContent.trim() === 'Check Requirements');\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:text=\"Check Requirements PHP\"i"], query: "whether the Check Requirements page heading is present", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The \"Check Requirements\" page is shown." }));
  });
  
  await testmu.step("Assertion check", { instructionId: "5d2cbc18-9561-4644-8977-b36dd50f5545" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The \"Check Requirements\" page is shown.", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{check_requirements_page_shown}}", "store_key": "check_requirements_page_shown", "variable_refs": {"{{check_requirements_page_shown}}": "true"}}], "sub_checks": [{"description": "The \"Check Requirements\" page is shown.", "store_key": "check_requirements_page_shown", "expected_value": "true", "extracted_value": "{{check_requirements_page_shown}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "the \"Check Requirements\" page is shown"});
  });
  
  await testmu.step("Clicking Continue in the LinkAce setup panel", { instructionId: "358e9656-0d38-432e-a823-6286a335156d" }, async () => {
    element_5 = page.locator("internal:role=link[name=\"Continue\"i]");
    await element_5.click();
  });
  
  await testmu.step("Waiting for the Database Configuration page to finish loading", { instructionId: "3809c635-c09e-47ce-9aa7-6ef12e5d15ef" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Checking whether the Database Configuration page is shown", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("database_configuration_page_shown", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {2: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(2).textContent.includes('Database Configuration'));\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:text=\"Database Configuration If you\"i"], query: "whether the Database Configuration page heading is present", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The \"Database Configuration\" page is shown." }));
  });
  
  await testmu.step("Assertion check", { instructionId: "426463af-bdda-47f1-b003-8994d58f9c61" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The \"Database Configuration\" page is shown.", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{database_configuration_page_shown}}", "store_key": "database_configuration_page_shown", "variable_refs": {"{{database_configuration_page_shown}}": "true"}}], "sub_checks": [{"description": "The \"Database Configuration\" page is shown.", "store_key": "database_configuration_page_shown", "expected_value": "true", "extracted_value": "{{database_configuration_page_shown}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "the \"Database Configuration\" page is shown"});
  });
  
  await testmu.step("Clicking Configure Database button", { instructionId: "5bebc9bd-6fdd-47da-98c7-c7900e63f1ff" }, async () => {
    element_6 = page.locator("internal:role=button[name=\"Configure Database\"i]");
    await element_6.click();
  });
  
  await testmu.step("Waiting for the Account Setup page to finish loading", { instructionId: "1dae9587-5803-4c84-afef-a84e21b1edf2" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Extracting the current page title", { instructionId: "8a3b0f3a-6597-4dd1-8d4f-05c8788ec903" }, async () => {
    setVar("account_setup_page_shown", await page.title());
  });
  
  await testmu.step("Assertion check", { instructionId: "8fd4bd78-aa0c-4ce9-958e-588e74bd5740" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "the \"Account Setup\" page is shown", "passed": true, "operator": "contains", "transforms": [], "json_path": null, "expected": "Account Setup", "extracted_value": "{{account_setup_page_shown}}", "store_key": "account_setup_page_shown", "variable_refs": {"{{account_setup_page_shown}}": "Account Setup - LinkAce"}}], "sub_checks": [{"description": "the \"Account Setup\" page is shown", "store_key": "account_setup_page_shown", "expected_value": "Account Setup", "extracted_value": "{{account_setup_page_shown}}", "operator": "contains", "transforms": []}], "composite_operator": "and", "claim": "the \"Account Setup\" page is shown"});
  });
  
  await testmu.step("Waiting for the plan to complete", { instructionId: "714475be-b39e-4359-8377-5030754d2a02" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Type \"alexmorgan\" into \"Username\"", { instructionId: "1b8ccd49-5b89-4f97-987e-5c3f34f457d6" }, async () => {
    element_0 = page.locator("internal:role=textbox[name=\"Username\"i]");
    await element_0.click();
    await element_0.fill("alexmorgan");
  });
  
  await testmu.step("Typing email address alex@example.com", { instructionId: "31d5848a-edda-4fb5-b8a6-3777f88e91d4" }, async () => {
    element_1 = page.locator("internal:role=textbox[name=\"Email\"i]");
    await element_1.click();
    await element_1.fill("alex@example.com");
  });
  
  await testmu.step("Typing password in password field", { instructionId: "704dc682-d7e1-44bb-a705-ea8e62bb567c" }, async () => {
    element_2 = page.locator("internal:role=textbox[name=\"Password\"s]");
    await element_2.click();
    await element_2.fill("LinkAce-Test-2026");
  });
  
  await testmu.step("Typing password confirmation", { instructionId: "19e851b3-e558-450f-849d-234e28e3def9" }, async () => {
    element_3 = page.locator("internal:role=textbox[name=\"linkace.password_confirmed\"i]");
    await element_3.click();
    await element_3.fill("LinkAce-Test-2026");
  });
  
  await testmu.step("Clicking create account submit button", { instructionId: "013c71ce-6e0c-415f-975f-c3478364f1a0" }, async () => {
    element_11 = page.locator("internal:role=button[name=\"Create account\"i]");
    await element_11.click();
  });
  
  await testmu.step("Clicking Go to the Dashboard navigation link", { instructionId: "bb0ef0ae-11ad-4e34-948d-9e3815339b66" }, async () => {
    element_12 = page.locator("internal:role=link[name=\"Go to the Dashboard\"i]");
    await element_12.click();
  });
  
  await testmu.step("Waiting for the LinkAce dashboard to finish loading", { instructionId: "69717f51-5c02-45df-9b56-dce7f94ec4d1" }, async () => {
    await page.waitForTimeout(1000);
  });
  
  await testmu.step("Checking that LinkAce setup has finished", async () => {
    await page.waitForLoadState("domcontentloaded", { timeout: 5000 });
    setVar("setup_finished_check", await testmu.textualAnalyzer(page, { wrappedJs: "(els) => {\n  const __m = {3: 0};\n  const el = (i) => els[__m[i]];\n  const __v = (el(3).getAttribute('href') === 'http://localhost:8096/dashboard' || el(3).getAttribute('href') === '/dashboard');\n  return (typeof __v === 'boolean' ? String(__v) : __v);\n}", locators: ["internal:role=link >> nth=0"], query: "whether the LinkAce setup has completed and the application dashboard is available", expectedValue: "true", needsUnitConversion: false, operator: "equals", transforms: [], condition: "The setup is finished." }));
  });
  
  await testmu.step("Reading the current LinkAce dashboard page title", { instructionId: "6dd51678-9788-4a85-b4d6-413a79a18896" }, async () => {
    setVar("linkace_dashboard_shown", await page.title());
  });
  
  await testmu.step("Assertion check", { instructionId: "a57883ad-02c3-43a8-8f6b-1504c5072578" }, async () => {
    await testmu.verifyAssertion(page, "Assertion check", {"operator": ["equals"], "assertion_operands": [], "left_operand": null, "right_operand": null, "operands": [], "sub_results": [{"description": "The setup is finished.", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "true", "extracted_value": "{{setup_finished_check}}", "store_key": "setup_finished_check", "variable_refs": {"{{setup_finished_check}}": "true"}}, {"description": "The LinkAce dashboard is shown.", "passed": true, "operator": "equals", "transforms": [], "json_path": null, "expected": "Dashboard - LinkAce", "extracted_value": "{{linkace_dashboard_shown}}", "store_key": "linkace_dashboard_shown", "variable_refs": {"{{linkace_dashboard_shown}}": "Dashboard - LinkAce"}}], "sub_checks": [{"description": "The setup is finished.", "store_key": "setup_finished_check", "expected_value": "true", "extracted_value": "{{setup_finished_check}}", "operator": "equals", "transforms": []}, {"description": "The LinkAce dashboard is shown.", "store_key": "linkace_dashboard_shown", "expected_value": "Dashboard - LinkAce", "extracted_value": "{{linkace_dashboard_shown}}", "operator": "equals", "transforms": []}], "composite_operator": "and", "claim": "the setup is finished and the LinkAce dashboard is shown"});
  });
});

testmu.run();