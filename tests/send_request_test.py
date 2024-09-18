from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()

def test_request_professional():
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
            email_field.send_keys("cristian.de.lauretis@gmail.com")
            password_field.send_keys("Qwerty1!")
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
    
    time.sleep(2)

    try:
        category_button = driver.find_element(By.CSS_SELECTOR, "button[data-category='colf']")
        if category_button:
            category_button.click()
            print("Categoria selezionata correttamente.")
        else:
            print("Errore nel trovare il pulsante della categoria idraulico.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante il clic sulla categoria: {e}")
        driver.quit()
        return


    try:
        professional = driver.find_element(By.CLASS_NAME, "worker-entry")
        if professional:
            professional.click()
            print("Professionista selezionato correttamente.")
        else:
            print("Errore nel trovare l'elemento del professionista.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante la selezione del professionista: {e}")
        driver.quit()
        return

    time.sleep(1)

    try:
        request_button = driver.find_element(By.CSS_SELECTOR, ".call-worker-button")
        if request_button:
            request_button.click()
            print("Pulsante di richiesta cliccato correttamente.")
        else:
            print("Errore nel trovare il pulsante di richiesta.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante il clic del pulsante di richiesta: {e}")
        driver.quit()
        return

    try:
        description_field = driver.find_element(By.ID, "request")
        if description_field:
            description_field.send_keys("Pulizia pavimento abitazione.")
            print("Descrizione del problema inserita correttamente.")
        else:
            print("Errore nel trovare il campo di descrizione.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante l'inserimento della descrizione: {e}")
        driver.quit()
        return

    try:
        send_button = driver.find_element(By.CSS_SELECTOR, ".send-request-button")
        if send_button:
            send_button.click()
            print("Richiesta inviata correttamente.")
        else:
            print("Errore nel trovare il pulsante di invio richiesta.")
            driver.quit()
            return
    except Exception as e:
        print(f"Errore durante l'invio della richiesta: {e}")
        driver.quit()
        return

    time.sleep(2)

    try:
        success_message = driver.find_element(By.CSS_SELECTOR, ".request-pending-container")
        if success_message.is_displayed():
            print("Test di richiesta al professionista superato con successo!")
        else:
            print("La richiesta non è stata inviata correttamente.")
    except Exception as e:
        print(f"Errore nel verificare il messaggio di successo: {e}")
    finally:
        driver.quit()


if __name__ == "__main__":
    test_request_professional()
