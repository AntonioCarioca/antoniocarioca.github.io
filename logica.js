function criptografar(){
    /**
     * Iniciar variaveis:
     * 1.Botão Criptografar
     * 2.Area do texto
     * 3.Transfromar texto em um vetor
     * 4.Area de componentes 1
     * 5.Area de componentes 2
     * 6.Area do texto Criptografado
     */
     let btn1 = document.querySelector('#btn1')
     let caixa = document.querySelector('#area').value

     /**
      * split() metodo para transformar uma string em um vetor
      */
      let vetor = caixa.split('')
      let bottom1 = document.querySelector('.bottom')
      let bottom2 = document.querySelector('.bottom2')
      let resultado = document.querySelector('#resultado')

      /**
     * Verificar se existe algum texto
     */
       if(caixa == ''){
        alert('Sem Mensagem')
    }
    else{

         /**
         * Fazer a varredura no vetor
         */
          for(let i = 0; i <= vetor.length; i++){

            if(vetor[i] == 'a'){
                vetor[i] = 'ai'
            }

            if(vetor[i] == 'e'){
                vetor[i] = 'enter'
            }

            if(vetor[i] == 'i'){
                vetor[i] = 'imes'
            }

            if(vetor[i] == 'o'){
                vetor[i] = 'ober'
            }

            if(vetor[i] == 'u'){
                vetor[i] = 'ufat'
            }
        }

        /**
         * 1.Esconder area de componentes 1
         * 2.Mostrar area de componentes 2
         */
         bottom1.style.display = 'none'
         bottom2.style.display = 'flex'

         /**
         * Exibir mensagem criptografada
         * join() metodo para unir elementos de
         * um vetor em uma String
         */
         resultado.innerHTML = vetor.join('')

         /**
         * Função para o botão de copiar texto
         */
        document.getElementById('copiar').addEventListener("click",function(){
            resultado.select()
            document.execCommand('copy')
        })
    }
}