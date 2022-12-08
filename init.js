function processMove(path, unsetfields, moveto) {
    var form = document.getElementById('content-edit'),
        unsetfields = typeof unsetfields != "undefined" ? '&unsetfields=' + unsetfields : '',
        moveto = typeof moveto != "undefined" ? '&moveto=' + moveto : '';
    
    querystring = form.action.split('?')[1];

    form.action = path + '?' + querystring + unsetfields + moveto;
    form.submit();
}
document.querySelector('.frwssr_moveitem__button').onchange = function() {
    processMove(this.dataset.path, this.dataset.unsetfields, this.value);
    return false;
};
