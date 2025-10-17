//FETCH: método de JS para obtener datos, permite hacer una peticion a cualquier ruta interna o externa y obtener datos
const container = document.querySelector('.certificates-grid')

fetch("./data/certificates.json")  /*Fetch es asincrono */
    .then((response) => {
        return response.json()
    })
    .then((certificates) => {     
        certificates.forEach(certificate => {
            const div = document.createElement('div')
            div.className = 'col-xs-12 col-sm-12'


            //Se crea plantilla interna del articulo con datos del json
            div.innerHTML = `<a href=${certificate.proof} class="lightbox">
                <div class="certificate-item clearfix">
                    <div class="certi-logo">
                        <img src=${certificate.logo} alt=${certificate.lightboxTitle}>
                    </div>
                    <div class="certi-content">
                        <div class="certi-title">
                        <h4>${certificate.title}</h4>
                        </div>

                        <div class="certi-id">
                        <span>${certificate.company}</span>
                        </div>
                        <div class="certi-date">
                        <span>${certificate.date}</span>
                        </div>
                    </div>                
                </div>
            </a>`

            container.appendChild(div) //Añade div dentro del contenedor certificates-grid
        })
    });
