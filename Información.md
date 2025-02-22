## Información:

* Desarrollado con VSCode y docker, configuración incluida para tener una experiencia consistente en el IDE
* Por ahorrar recursos he usado el tipo de DB de sqlite, ya que usa otra db relacional es solo cambiar la configuración


### Endpoints
    Todos los endpoints menos Login, requieren el uso de bearer token
*  Login: POST JSON **/api/login**

```json

 Body {"email": "asd@example.com", "password": "password"}
 Response {"token": "asdasd", "expiresAt":"2025-12-31 23:59"}
 ```

*  List Payment Providers: GET JSON **/api/payment_provider**
```json

 Response [
    {
        "name": "EasyMoney",
        "uuid": "e9e04909-bbb7-32d3-a916-108dc2087bd1",
        "url": "http://localhost:3000/process",
        "status": "active",
        "created_at": "2025-12-31 23:59",
        "updated_at": "2025-12-31 23:59"
    }
]

 ```
*  Payment: POST JSON **/api/payment/{provider_uuid}**
```json

 Body {"amount": "1", "currency": "EUR"}
 Response [
    {
        "success": true,
        "transaction_id": "e9e04909-bbb7-32d3-a916-108dc2087bd1"
    }
]
 ```
*  Webhook: POST JSON **/api/webhook/{transaction_uuid}**
