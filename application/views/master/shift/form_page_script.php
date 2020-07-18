<script>
    $(function() {
        $('#jumlah_shift').keyup(function() {
            var waktuHtml = '';
            for (var i = 1; i <= toFloat($('#jumlah_shift').val()); i++) {
                waktuHtml += '<tr>';
                    waktuHtml += '<td><input type="text" name="waktu['+i+'][shift_waktu]" class="form-control input-sm"></td>';
                    waktuHtml += '<td>';
                        waktuHtml += '<div class="input-group bootstrap-timepicker">';
                            waktuHtml += '<input type="text" name="waktu['+i+'][jam_mulai]" value="0" class="form-control input-sm" data-input-type="timepicker">';
                            waktuHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
                        waktuHtml += '</div>';
                    waktuHtml += '</td>';
                    waktuHtml += '<td>';
                        waktuHtml += '<div class="input-group bootstrap-timepicker">';
                            waktuHtml += '<input type="text" name="waktu['+i+'][jam_selesai]" value="0" class="form-control input-sm" data-input-type="timepicker" onchange="set_next_shift('+i+')">';
                            waktuHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
                        waktuHtml += '</div>';
                    waktuHtml += '</td>';
                waktuHtml += '</tr>';
            }
            $('#waktu tbody').html(waktuHtml);
            $('#waktu tbody').buildForm()
        });
    });

    function set_next_shift(urutan) {
        var jam_selesai = $('[name="waktu['+urutan+'][jam_selesai]"').val();
        $('[name="waktu['+(urutan+1)+'][jam_mulai]"').val(jam_selesai);
    }
</script>