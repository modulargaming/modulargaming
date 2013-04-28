var selected = {specie:1, colour:1};

function pet_image() {
    $('#pet_image').attr('src', base_url + pet_data.species[selected.specie].dir + '/' + pet_data.colours[selected.colour].img);
}

$(document).ready(function () {

	// Only run on pet create.
	if ( ! $('body').hasClass('pet-create')) {
		return;
	}

    $("a[rel=popover]").click(function (e) {
        e.preventDefault();

        selected.specie = $(this).data('specie');

        $.each(pet_data.colours, function (colour, data) {
            if (pet_data.species[selected.specie].colours.indexOf(parseInt(colour)) < 0) {
                $('button.pet_colour[value="' + colour + '"]').addClass('disabled');
            }
            else {
                $('button.pet_colour[value="' + colour + '"]').removeClass('disabled');
            }
        });

        $('#specie_id').val(selected.specie);
        pet_image();
        $('#pet_specie').text($(this).data('original-title'));
        $('#pet_description').text($(this).data('content'));
    }).popover();

    $(".pet_colour").click(function () {
        selected.colour = $(this).val();
        $('#pet_colour').text(pet_data.colours[selected.colour].name);
        $("#colour_id").val(selected.colour);
        pet_image();
    });

    $(".gender button").click(function () {
        $("#gender").val($(this).val());
    });
});