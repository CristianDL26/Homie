from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time


driver = webdriver.Chrome(executable_path='/Users/cristiandelauretis/Desktop/chromedriver')

def test_login():
    driver.get("http://localhost/login_page.php")  

    email_field = driver.find_element(By.NAME, "email")
    password_field = driver.find_element(By.NAME, "password")
    email_field.send_keys("angelealessia74@gmail.com")
    password_field.send_keys("Telecomando1!")

    login_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
    login_button.click()

    time.sleep(2)

    dashboard_element = driver.find_element(By.ID, "userid")  
    assert dashboard_element.is_displayed(), "Il login non è riuscito."

    print("Test di login superato con successo!")

    driver.quit()


if _name_ == "_main_":
    test_login()