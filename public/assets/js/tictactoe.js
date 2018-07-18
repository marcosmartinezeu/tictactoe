var boardState = [];
var moveHistory = [];
var nextMove = {};
var isFinished = false;
var gameResult = false;
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
            gameResult: gameResult,
            winner: winner,
            isFinished: isFinished
        }),
        processData: false,
        contentType: 'application/json'
    }).done(function (data) {
        // Check matchId
        if (data.matchId != $('table').attr('id')) {
            // Error
            isFinished = true;
            gameResult = 'error';
            showMessage('Error', 'Server error: matchId different')

        }
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
        var result = gameResult;
        if (result != 'tie') {
            if (winner == 'o') {
                result = 'You win!'
            }
            if (winner == 'x') {
                result = 'You lose!'
            }
        }
        showMessage('Game Over', result);
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
    gameResult = response.gameResult;
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

var showMessage = function(title, text) {
    $('#message-container').removeClass('hide');
    $('#message-title').html(title);
    $('#message-text').html(text);
};

// Init
var init = function() {
    initEvents();
    renderTable();
    updateBoardState();
    updateHistoryResponse();
};

// Events
var initEvents = function () {
    $("td").on("click", clickBoard);
    $("#reset-btn").on("click", resetBoard);
};

var clickBoard = function() {
    if (isFinished === false && $(this).data('value') != 'o' && $(this).data('value') != 'x') {
        $(this).html("<i class='far fa-circle fa-5x'></i>");
        $(this).data("value", "o");
        setNextMove($(this).data('value'), $(this).data('position'));
        callApi();
    }
};

var resetBoard = function () {

    // Reset vars
    boardState = [];
    moveHistory = [];
    nextMove = {};
    isFinished = false;
    gameResult = false;
    winner = '';

    // Reset table
    $("td").each(function () {
        $(this).data('value', '');
        $(this).html('');
    });
    renderTable();

    // Hide message
    $('#message-container').addClass('hide');
    $('#message-title').html('');
    $('#message-text').html('');
};

$(document).ready(function(){
    init();
});