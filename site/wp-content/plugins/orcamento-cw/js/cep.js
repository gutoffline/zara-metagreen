$(document).ready( function() {
   /* Executa a requisição quando o campo CEP perder o foco */
   $('#cep').blur(function(){
           /* Configura a requisição AJAX */
           $('#load_cep').html("<img src='/images/loader.gif' style='width:30px'> ");
           $('#load_cep').show();
           $.ajax({
                url : '/wp-content/themes/arco-iris/consulta_cep.php', /* URL que será chamada */ 
                type : 'POST', /* Tipo da requisição */ 
                data: 'cep=' + $('#cep').val(), /* dado que será enviado via POST */
                dataType: 'json', /* Tipo de transmissão */
                success: function(data){
                    if(data.sucesso == 1){
                        $('#load_cep').html("<span class='glyphicon glyphicon-ok' style='color: rgb(0, 226, 0);font-size: 20px;text-shadow: 1px 3px 4px #494949;'></span>");
                        $('#rua').val(data.rua);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.cidade);
                        $('#estado').val(data.estado);
 
                        $('#numero').focus();
                        $('#load_cep').html(" ");
                    }else{
                    $('#load_cep').html("<span style='color:red'>CEP Inválido</span>");
                    }
                }
           });   
   return false;    
   })
});