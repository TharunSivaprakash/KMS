from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# WebDriver Manager for automatic driver setup
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service

# Setup Chrome browser
options = webdriver.ChromeOptions()
options.add_argument("--headless")  # Run in background (optional)

# Start WebDriver
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=options)

# Step 1: Open VTOP login page
driver.get("https://vtop.vit.ac.in")  

# Step 2: Click the "Student" button
wait = WebDriverWait(driver, 10)
student_button = wait.until(EC.element_to_be_clickable((By.XPATH, "//button[contains(text(),'Student')]")))
student_button.click()

# Step 3: Wait for the login form to load
wait.until(EC.presence_of_element_located((By.ID, "username")))

# Step 4: Enter login details (Replace with your actual credentials)
username = driver.find_element(By.ID, "username")
password = driver.find_element(By.ID, "password")

username.send_keys("22MIS0372")
password.send_keys("Sivaprakash@004")

# CAPTCHA - This needs manual input unless solved via automation
input("Solve the CAPTCHA in the browser and press Enter...")

# Submit the form
password.send_keys(Keys.RETURN)

# Step 5: Wait for login to complete
time.sleep(5)

# Verify login success
if "Dashboard" in driver.page_source:
    print("Login successful!")
else:
    print("Login failed. Check credentials or CAPTCHA.")

# Continue with scraping the exam schedule...
