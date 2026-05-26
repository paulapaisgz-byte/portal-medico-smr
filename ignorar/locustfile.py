from locust import HttpUser, task, between

class PacienteVirtual(HttpUser):
    # Tiempo de espera entre acciones (simula a un humano leyendo)
    wait_time = between(1, 3) 

    @task(1)
    def ver_formulario(self):
        # Simula cargar la interfaz nativa HTML5/CSS3
        self.client.get("/portal-medico-smr/portal.php")

    @task(2)
    def reservar_cita(self):
        # Simula el envío de datos por POST a guardar_cita.php
        # Usamos datos de ejemplo que vuestra DB acepte
        payload = {
            "medico": "Dr. Garcia",
            "fecha": "2026-05-15",
            "hora": "10:00",
            "usuario_id": "1"  # Aseguraos que este ID existe para evitar el error de FK
        }
        self.client.post("/portal-medico-smr/guardar_cita.php", data=payload)