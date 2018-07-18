var boardState = [];
var moveHistory = [];
var nextMove = {};
var isFinished = false;
var result = false;
var winner = '';

var callApi = function () {
    var playerUnit = "X";
    $.ajax({
        url: "play",
        method: "POST",
        data: JSON.stringify({
            matchId: $('table').attr('id'),
            boardState: boardState,
            nextMove: nextMove,
            history: moveHistory,
            result: result,
            winner: winner,
            isFinished: isFinished
        }),
        processData: false,
        contentType: 'application/json'
    }).done(function (data) {
        // Check matchId

        // loadInfoFromResponse
        loadInfoFromResponse(data);

        // Make new move
        makeNewMove(data.nextMove);

    });
};

var renderTable = function(){
    $("td").each(function () {
        if ($(this).data('value') == 'x') {
            $(this).html("<i class='fa fa-times fa-5x'></i>");
        }

        if ($(this).data('value') == 'o') {
            $(this).html("<i class='far fa-circle fa-5x'></i>");
        }

    });

    if (isFinished == true) {
        $('#result-container').removeClass('hide');
        $('#result').html(result);
        if (result != 'tie') {
            $('#winner').removeClass('hide');
            if (winner == 'o') {
                $('#winner').html('HUMAN')
            }

            if (winner == 'x') {
                $('#winner').html('MACHINE')
            }


        }

    }
};
var updateTable = function() {
    $("td").each(function () {
        $(this).data('value', boardState[$(this).data('position')]);
    });
    renderTable();
};
var loadInfoFromResponse = function(response) {
    boardState = response.boardState;
    moveHistory = response.history;
    nextMove = {};
    isFinished = response.isFinished;
    result = response.result;
    winner = response.winner;
    updateTable();
};

var makeNewMove = function(nextMove){
    moveHistory.push(nextMove);
    boardState[nextMove.position] = nextMove.char;
    updateTable();
};

var updateBoardState =  function() {
    $("td").each(function () {
        boardState[$(this).data('position')] = $(this).data('value');
    });
};

var addMoveToHistory = function (char, position) {
    moveHistory.push({'char': char, 'position': position});
};

var setNextMove = function (char, position) {
    nextMove = {'char': char, 'position': position};
};

var updateHistoryResponse =  function() {
    updateBoardState();
    boardState.forEach(function(char, position){
        if (char == 'x' || char == 'o') {
            addMoveToHistory(char, position);
        }
    });
};

var init = function() {
    initEvents();
    renderTable();
    updateBoardState();
    updateHistoryResponse();
};

var initEvents = function () {
    $("td").on("click", clickBoard);
};

var clickBoard = function() {
    if (isFinished === false) {
        $(this).html("<i class='far fa-circle fa-5x'></i>");
        $(this).data("value", "o");
        setNextMove($(this).data('value'), $(this).data('position'));
        callApi();
    }
};

$(document).ready(function(){
    init();
});