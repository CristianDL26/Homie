from selenium import webdriver
from selenium.webdriver.common.by import By
import time


driver = webdriver.Chrome(executable_path='/Users/cristiandelauretis/Desktop/chromedriver')

def test_request_professional():

    driver.get("http://localhost/home.php")  


    category_button = driver.find_element(By.CSS_SELECTOR, "button[data-category='elettricista']")
    category_button.click()

    time.sleep(2)

    professional = driver.find_element(By.ID, "professional-name")
    professional.click()

    time.sleep(1)

    description_field = driver.find_element(By.ID, "request")
    description_field.send_keys("Problema elettrico con prese multiple non funzionanti.")

    send_button = driver.find_element(By.CSS_SELECTOR, ".send-request-button")
    send_button.click()

    time.sleep(2)

    success_message = driver.find_element(By.CSS_SELECTOR, ".request-pending-container")
    assert success_message.is_displayed(), "La richiesta non è stata inviata correttamente."

    print("Test di richiesta al professionista superato con successo!")

    driver.quit()


if _name_ == "_main_":
    test_request_professional()