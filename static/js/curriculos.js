/**
 * Objeto que permite receber todos os apontamentos da base de dados, 
 * cadeiras e respetivos anos e semestre associados.
 * 
 * É de notar que os ficheiro podem apenas ser acedidos quando o login se encontra efectuado
 * devido às permissões estabelecidas no acesso a esses mesmos ficheiros.
 * 
 * O link base para ser estabelecido o acesso é:
 *      https://nei.web.ua.pt/ealuno_dwn.php?url=curriculos/
 * A partir daqui devem ser gerados todos os acessos com base num ficheiro php
 * que liga todo o website à db do NEI.
 * 
 * @author rc
 */

$(document).ready(function () {
    var CurriculosInfo = {
        "1" : [
                //{ autor : "Nome ", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=curriculos/primeiro_ano/CV.pdf", data: "29-10-2018"},
            ],
        "2" : [
                //{ autor : "Nome", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=curriculos/segundo_ano/CV.pdf"},
            ],
        "3" : [
                { autor : "João Rodrigues", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=curriculos/terceiro_ano/JoaoRodrigues.pdf", data: "12-11-2018"},
                { autor : "Rui Coelho", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=curriculos/terceiro_ano/RuiCoelho.pdf", data: "29-10-2018"},
                { autor : "Rafael Teixeira", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=curriculos/terceiro_ano/RafaelTeixeira.pdf", data: "29-10-2018"}
                ],
        "4" : [
                    //{ autor: "João Alegria", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=apontamentos/mestrado/aa/aa_apontamentos_001.pdf"},
                ],
        "5" : [
                //{ autor: "João Alegria", link : "https://nei.web.ua.pt/ealuno_dwn.php?url=apontamentos/mestrado/aa/aa_apontamentos_001.pdf"},
            ],
    };
    // #region CRIAÇÃO DE ELEMENTOS 

    // Nota: Se mais tarde forem feitas funções para a criação de elementos, colocar-las aqui
    /**
     *  
     * 
     * Retorna um elemento (jQuery) utilizado como cabeçalho
     * Se o tamanho não for especificado, cria um elemento h2
     * @param {string} headerText Texto do cabeçalho
     * @param {number} size Tamanho do elemento (h1, h2, etc)
     * * @author Miguel Fradinho Alves
     */
    function createApontHeader(headerText, size = 2) {
        // para evitarmos passagem incorreta
        if (size < 1 || size > 6) {
            size = 2;
        }
        const headerElem = $("<h" + size + ">").addClass("apont-header col-12 u-break-word");
        // verificamos se foi chamado com texto (tecnicamente, nunca deve acontecer, 
        // mas just in case no caso de passagem de variáveis)
        if (typeof headerText !== "undefined") {
            headerElem.text(headerText);
        }
        return headerElem;

    }

    /**
     * Cria uma entrada do cabeçalho de um tabela
     * @param {string} value valor para o cabeçalho
     * @param {string} classes string com as classes a adicionar (separadas por espaços)
     * @author Miguel Fradinho Alves
     */
    function createTableHeader(value, classes = undefined) {
        return $("<th>").text(value).addClass(typeof classes === "undefined" ? "" : classes);
    }

    /**
     * Cria uma linha de uma tabela
     * @param {string} classes string com as classes adicionar (separadas por espaços)
     * @author Miguel Fradinho Alves
     */
    function createTableRow(classes = undefined) {
        return $("<tr>").addClass(typeof classes === "undefined" ? "" : classes);
    }

    /**
     * Cria uma entrada de tabela.
     * Se o valor fornecido for um link, então cria automaticamente o elemento <a> para ser possível de clicar
     * @param {string} value valor da entrada
     * @param {string} classes string com as classes adicionar (separadas por espaços)
     * @author Miguel Fradinho Alves
     */
    function createTableData(value, classes = undefined) {
        const linkRegex = /(\b(http(s)?):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gmi;
        if (value.match(linkRegex)) {
            const valueElem = $("<a>").text("Aceder ao currículo").attr({
                "href": value,
                "target": "_blank"
            });
            return $("<td>").addClass(typeof classes === "undefined" ? "" : classes).append(valueElem);
        }
        return $("<td>").text(value).addClass(typeof classes === "undefined" ? "" : classes);
    }

    /**
     * Cria uma linha de uma tabela a partir de um determinado objeto.
     * Apenas cria com base nas colunas desejadas, passadas como array ao parametro attributes
     * @param {Array<string>} attributes atributos a criar na linha
     * @param {object} obj objeto com os dados (Nota: deve ter pelo menos os mesmo atributos que os passados coomo argumento)
     * @author Miguel Fradinho Alves
     */
    function createRowFromObject(attributes, obj) {
        // create the container we're going to use
        const row = createTableRow();
        // for each key in the object
        // TODO: Maybe make this match the headers?
        for (const key of attributes) {
            // if the object provided has the attribute
            if (obj.hasOwnProperty(key)) {
                // append a table data element
                row.append(createTableData(obj[key]));
            }
            // if it doesn't, then we should still create an empty table data
            else{
            	row.append(createTableData("-"));
            }
        }
        return row;
    }

    /**
     * Cria uma tabela a partir de um array dos cabeçalhos
     * e um array de objetos.
     * Nota: as rows têm a mesma ordem das colunas que as headers fornecidas
     * @param {Array<Array<string>>} headers Uma lista de listas com 2 elementos, representando sendo que o 1º elemento é o nome da propriedade 
     * e o segundo como deve ser mostrado
     * @param {Array<object>} rows lista dos objetos
     * @author Miguel Fradinho Alves
     */
    function createTable(headers, rows) {
        // we create our table object
        const table = $("<table>").addClass("table table-striped table-hover");
        // create the head and body
        const tableHead = $("<thead>");
        const tableBody = $("<tbody>");
        // create the row for the headers
        const headersRow = createTableRow();
        // so we can later pass that down to the object
        const propertyHeaders = [];

        // create all headers and append them to the headersRow
        headers.forEach(header => {
            headersRow.append(createTableHeader(header[1]));
            propertyHeaders.push(header[0]);
        });
        // add the headers row to the tableHead
        tableHead.append(headersRow);
        // and append to the table
        table.append(tableHead);
        // add all rows to the body
        for (const row of rows) {
            tableBody.append(createRowFromObject(propertyHeaders, row));
        }

        // add the body to the tableBody
        table.append(tableBody);
        // and finally return the table
        return table;
    }

    /**
     * Retorna um botão com um handler associado (Nota: De momento, apenas consideramos voltar atrás no ano)
     * @author Miguel Fradinho Alves
     */
    function createBackButton() {
        function back_button_handler() {
            $("#anos").removeClass("hide");
            $("#displayer").addClass("hide");
        };
        const button = $("<button>")
            .addClass("btn apont-button col-12 offset-md-4 col-md-4 offset-lg-2 col-lg-2")
            .on("click", back_button_handler);
        const arrowIcon = $("<i>").addClass("fa fa-arrow-left").css("font-size", "45px");
        return button.append(arrowIcon);
    }

    /**
     * Retorna um div, com classes caso sejam passadas como argumento
     * @param {Array<string>} classes lista das classes a adicionar ao container, opcional
     * @author Miguel Fradinho Alves
     */
    var createContainer = function (classes = undefined) {
        const elem = document.createElement("div");
        // se não for passado
        if (typeof classes !== 'undefined') {
            // se apenas for um
            if (typeof classes === 'string') {
                elem.classList.add(classes);
            }
            // se for um array
            if (Array.isArray(classes)) {
                elem.classList = classes
            }
        }
        return elem;
    }
    /**
     * Retorna um elemento correspondente a um item de uma lista
     * @author Miguel Fradinho Alves
     */
    var createListItem = function (itemText) {
        const elem = document.createElement("a");
        const elem_classes = ["list-group-item", "list-group-item-action"];
        for (let i = 0; i < elem_classes.length; i++) {
            elem.classList.add(elem_classes[i]);
        }
        elem.innerText = itemText;
        return elem;
    }

    //#endregion
    // for keeping track of last year
    let previousYear = undefined;
    /**
     * Função para fazer display dos curriculos para um dado ano.
     * Nota: Apenas faz o re-render da tabela se o botão do ano a ser clicado for diferente do anterior
     * @param {string} year 
     * @author Miguel Fradinho Alves
     */
    function getCurriculumForYear(year) {
        // we only need to re-render everything if the year is different
        // but we'll still display
        if (previousYear === year) {
            // we hide the year selection
            $("#anos").addClass("hide");
            // and display
            $("#displayer").removeClass("hide");
            return;
        }
        // Otherwise, we need to re-render

        // safety check
        if ((!year in CurriculosInfo || typeof CurriculosInfo[year] === 'undefined')) {
            return;
        }
        // store the year
        previousYear = year;

        // we hide the year selection
        $("#anos").addClass("hide");
        // get the displayer of data, clear it and  allow it to be showed
        const disp = $("#displayer").empty().removeClass("hide");

        // adicionamos um header com o ano selecionado
        disp.append(
            createApontHeader("Curriculos - " + year + "º Ano")
        );
        // get the elements for the current year
        const yearElems = CurriculosInfo[year];
        // create and append the table to the displayer;
        // TODO: Nota não esquecer mudar o JSON e atualizar os headers
        console.log("Nota: não esquecer mudar o JSON e atualizar os headers");
        const headerMappings = [
            ["autor", "Nome"],
            ["link", "Currículos"],
            ["data", "Data de inserção"]
        ];
        const tableElem = createTable(headerMappings, yearElems);
        disp.append(tableElem);
        // and create the back button
        const backElem = createBackButton();
        disp.append(backElem);

    }

    /**
     * Função para tratar dos handlers para os botões
     * @author Miguel Fradinho Alves
     */
    function setupButtons() {

        function btn_click() {
            // get the year value for whichever button was clicked
            const year = $(this).data("year");
            // and display the curriculums for a given year
            getCurriculumForYear(year);
        }
        //Adicionar o handler aos botoes e aos filhos
        // (porque bubble-up e bubble-down)
        $(".apont-button").on("click", btn_click);
        $(".apont-button").children().on("click", btn_click);
    }
    /**
     * Função para agrupar todos os handlers
     * (Nota: neste momento só tem os botões)
     * @author Miguel Fradinho Alves
     */
    function setupHandlers() {
        setupButtons();
    }

    setupHandlers();

});