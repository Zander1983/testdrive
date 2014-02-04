/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

        alert('in the js');
        $('#project_id').change(function() {
            if ($(this).val()) {
                    alert('in show');

                    $.ajax({
                        url: 'admincreate',
                        data: { 'project_id': $(this).val()},
                        success: function(data) { 
                            console.log('data is ');
                            console.log(data);
                          //  $('#device_list').append('<input type='checkbox' value='test' name='device_id' />');

                        }
                    });

            } else {
                   alert('in hide');
            }

        });

                