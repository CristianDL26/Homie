from selenium import webdriver
from selenium.webdriver.common.by import By
import time


driver = webdriver.Chrome()

def test_login():
    try:
        driver.get("http://localhost/Homie/login_page.php")
        print("Pagina di login aperta correttamente.")
    except Exception as e:
        print(f"Errore nell'aprire la pagina di login: {e}")
        driver.quit()
        return

    try:
        email_field = driver.find_element(By.NAME, "email")
        password_field = driver.find_element(By.NAME, "password")
        if email_field and password_field:
            email_field.send_keys("angelealessia74@gmail.com")
            password_field.send_keys("Telecomando1!")
            print("Credenziali inserite correttamente.")
        else:
            print("Errore nel trovare i campi di email o password.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante l'inserimento delle credenziali: {e}")
        driver.quit()
        return

    try:
        login_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
        if login_button:
            login_button.click()
            print("Login eseguito correttamente.")
        else:
            print("Errore nel trovare il pulsante di login.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante il clic del pulsante di login: {e}")
        driver.quit()
        return

    time.sleep(2)

    try:
        dashboard_element = driver.find_element(By.CLASS_NAME, "username")
        if dashboard_element.is_displayed():
            print("Login avvenuto con successo. Nome utente visibile.")
        else:
            print("Login fallito, nome utente non visibile.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore nel verificare la dashboard dopo il login: {e}")
        driver.quit()
        return


if __name__ == "__main__":
    test_login()