$('#uvjet').autocomplete({
    source: function(req,res){
        $.ajax({
            url:'/igrac/traziigrace',
            data:{
                uvjet: req.term,
                klub: klub
            },
            success: function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select: function(dogadaj,ui){
        spremi(klub,ui.item);
    }
}).autocomplete('instance')._renderItem=function(ul,igrac){
    return $('<li>').append('<div>' + igrac.ime + ' ' + igrac.prezime +
    '</div>').appendTo(ul);
};

function spremi(klub,igrac){
    //console.log('klub:' + klub);
    //console.log('igrac:' + igrac.sifra);
    $.ajax({
        type:'POST',
        url:'/klub/dodajigraca',
        data:{
            igrac: igrac.sifra,
            klub: klub
        },
        success: function(odgovor){
            if(odgovor==='OK'){
                $('#igraci').append('<tr>' +
                '<td>' + igrac.ime + ' ' + igrac.prezime + '</td>' +
                '<td>' +
                '<a class="brisanje" href="#" id="p_' + igrac.sifra + '">' +
                    '<i title="brisanje" style="color: red" class="fas fa-trash-alt" aria-hidden="true"></i><span class="sr-only">brisanje</span>' +
                '</a>' +
                '</td>' +
            '</tr>');
            definirajBrisanje();
            }else{
                alert(odgovor);
            }
        }
    });
}

function definirajBrisanje(){
    $('.brisanje').click(function(){
        let element=$(this);
        let sifra = element.attr('id').split('_')[1];
        //console.log('klub:' + klub);
        //console.log('igrac:' + sifra);
        $.ajax({
            type:'POST',
            url:'/klub/obrisiigraca',
            data:{
                igrac: sifra,
                klub: klub
            },
            success: function(odgovor){
                if(odgovor==='OK'){
                    element.parent().parent().remove();
                }else{
                    alert(odgovor);
                }
               
            }
        });
        return false;
    });
}
definirajBrisanje();