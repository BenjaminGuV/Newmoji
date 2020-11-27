(function() {
 
    //get class to apply events
    var only_nemoji = document.getElementsByClassName("only_nemoji");
    
    //asign events to emojis
    for(var i = 0; i < only_nemoji.length; ++i){

        only_nemoji[i].onmouseover = function() {
            removeClass(this, "off");
            addClass(this, "on");

            this.onmouseout = function() {
                removeClass(this, "on");
                addClass(this, "off");
            }
            
        }

        only_nemoji[i].onclick = function(e){
            //your handler here

            let action_emoji = this.getAttribute('data-info');
            let hashinfo     = this.getAttribute('data-hashinfo');

            let name_hash = 'h_newmoji_hash_' + hashinfo;

            let h_hash       = document.getElementById( name_hash ).value;
            
            const datas = new FormData();
            datas.append('action', 'save_newmoji_ajax');
            datas.append('action_emoji', action_emoji);
            datas.append('h_hash', h_hash);

            this.path_ajax = localize_vars.url + "wp-admin/admin-ajax.php";

            postData( this.path_ajax , datas)
            .then( data => {
                // JSON data parsed by `data.json()` call

                if ( data.status == "OK" ) {
                    let hash_code = data.data.hash_code;
                    var tmp_html = `<div class="row">
                                        <div class="col-nwe-12">
                                            <h5>Su reacción ya fue capturado</h5>
                                        </div>
                                    </div>`;

                    this.name_code     = 'cont_id_newmoji_' + hash_code;
                    var c_cont_newmoji = document.getElementById( this.name_code );
                    
                    c_cont_newmoji.innerHTML =  tmp_html;
                            
                } else {
                    alert( data.message );
                }

            })
            .catch(error => console.error('Error:', error));
            

        }

    }

    //data

    

    function removeClass(element, className) {
        element.classList.remove(className);
    }

    function hasClass(element, className) {
        element.classList.contains(className);
    }

    function addClass(element, className) {
        element.classList.add(className);
    }

 })();


 // Ejemplo implementando el metodo POST:
async function postData(url = '', data) {
    // Opciones por defecto estan marcadas con un *
    const response = await fetch(url, {
      method: 'POST', // *GET, POST, PUT, DELETE, etc.
      body: data // body data type must match "Content-Type" header
    })

    return response.json(); // parses JSON response into native JavaScript objects
}