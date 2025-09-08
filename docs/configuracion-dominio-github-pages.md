# 🌐 Dominio Web Portfolio CV Laura

A partir de **GitHub Pages**, donde puedes publicar tu web desde un repositorio con dominio `github.io`, hemos conseguido usar GitHub como **host** y únicamente comprar el dominio propio.

---

## 🛒 Compra del dominio en IONOS

- **Dominio:** `lauratb.es`  
- **Precio:** 1,21 € el primer año, 10 €/año siguientes  
- **Fecha de compra:** 07/09/2025  
- **Panel de gestión:** [my.ionos.es/domains](https://my.ionos.es/domains?filter.type=domain&page.page=0&filter.excludeCancelled=false&filter.tld=any&page.size=10&filter.search=lauratb.es&filter.phase=any)

> **Nota:** credenciales de acceso no se incluyen aquí por seguridad.

---

## 🔧 Conexión del dominio con GitHub Pages

### 1. Configurar dominio en GitHub Pages
1. Ve a tu repositorio → **Settings → Pages**  
2. En **Custom domain**, escribe tu dominio (`lauratb.es`) y pulsa **Save**  
3. GitHub mostrará los registros DNS que debes añadir en tu proveedor de dominio (IONOS)

### 2. Configurar DNS en IONOS
1. Accede a **Dominios y SSL** en IONOS  
2. Selecciona tu dominio y entra en **DNS**  
3. **Elimina registros A/AAAA existentes**  
   - Si no te deja borrarlos, desactiva antes el servicio **Default Site**
4. Añade los registros indicados por GitHub:
   - **4 registros A** apuntando a las IPs de GitHub Pages:
     - `185.199.108.153`
     - `185.199.109.153`
     - `185.199.110.153`
     - `185.199.111.153`
   - **1 registro CNAME** para `www` → `laura-tb.github.io`

> ⚠️ **Importante:** Deja intactos los registros MX y TXT si tienes correo configurado.

### 3. Esperar propagación de DNS
- La propagación puede tardar varias horas (hasta 24 h)
- Comprobar con [whatsmydns.net](https://www.whatsmydns.net/) (tipo **A** para `lauratb.es`)

### 4. Verificar y activar HTTPS en GitHub
- Vuelve a **Settings → Pages** en tu repositorio
- Pulsa **Check again** hasta que aparezca ✅ **DNS check successful**
- Activa **Enforce HTTPS** (GitHub emitirá gratis un certificado Let's Encrypt)

### 5. Comprobar funcionamiento
- Abre [https://lauratb.es](https://lauratb.es) en el navegador  
- Si todo está correcto, se mostrará tu web alojada en GitHub Pages

---

## 📌 Notas
- No hace falta contratar el SSL de IONOS. GitHub emitirá gratis un certificado de Let's Encrypt en cuanto valide el dominio.
- GitHub se encarga de generar y renovar automáticamente el certificado.
- Si en el futuro cambias de dominio, basta con actualizar los registros en IONOS y el campo **Custom Domain** en GitHub.

