<!-- start jQuery script -->
<script>
  $(document).ready(function () {
    console.log('ready');
    loadDoctors();

    var scheduleList = [];
    var editScheduleList = [];

      // START LOAD DOCTOR FUNCTION
    function loadDoctors() {
    $.ajax({
        type: 'GET',
        url: 'handles/doctors/read_doctors.php',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            $('#tbodyDoctors').empty();

            response.doctor_data.forEach(function(data) {
                const read_doctor_html = `
                    <tr>
                        <th scope="row"><small>${data.doctor_id}</small></th>
                        <td><small>${data.full_name}</small></td>
                        <td><small>${data.schedule}</small></td>
                        <td><small>${data.contact}</small></td>
                        <td data-doctor-id='${data.doctor_id}'>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end text-center">
                                <button id='callEdit' type='button' class='btn btn-mymedium btn-sm' data-bs-toggle='modal' data-bs-target='#mod_editDoc'><i class="fas fa-edit"></i></button>
                                <button id='callDelete' type='button' class='btn btn-myshadow btn-sm' data-bs-toggle='modal' data-bs-target='#mod_delDoc'><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
                $('#tbodyDoctors').append(read_doctor_html);
            }); // END EACH FUNCTION
        },
        error: function(error) {
            console.log("READ DOCTOR ERROR:", error);
        }
    });
}


      // START DELETE DOCTOR
      $(document).on('click', '#callDelete', function() {
        var doctor_id = $(this).closest("td").data('doctor-id');
        var doctor_name = $(this).closest("td").data('doctor-name');

        console.log("doctor id:", doctor_id, "doctor name:", doctor_name);
        $('#delDrName').text(doctor_name);

        $('#btnDel').data('doctor-id', doctor_id);
      });


      // $(document).on('click', '#callDelete', function() {
      //   var doctor_id = $(this).closest("td").data('doctor-id');
      //   var service_name = $(this).closest("td").data('doctor-name');

      //   console.log("doctor id:", doctor_id, "doctor name:", service_name);
      //   $('#servName').text(service_name);

      //   $('#btnDel').data('doctor-id', doctor_id);
      // });

      $(document).on('click', '#btnDel', function() {
        var doctor_id = $(this).data('doctor-id');
        var user_input = $('#del_user_input').val();

        if (user_input !== 'DELETE') {
          alert('Please type DELETE to confirm.');
          return;
        }

        $.ajax({
          type: 'POST',
          url: 'handles/doctors/delete_doctor.php',
          data: {doctor_id: doctor_id, user_input: user_input},
          dataType: 'JSON',
          success: function(response) {
            console.log("DELETE DOCTOR RESPONSE:", response);
            loadDoctors();
            $('#mod_delDoc').modal('hide');
          },
          error: function(error) {
            console.log("DELETE DOCTOR ERROR:", error);
          }
        });
      });


      // CALL ADD SCHEDULE
      $('#callSetSched').click(function () {

        new bootstrap.Modal($('#mod_addDocSched')).show();
      });

      // ADD SCHEDULE
      $('#addSched').click(function () {

        var day_of_week = $('#day_of_week').val();
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        
        if (!day_of_week || !start_time || !end_time) {
          alert('Please complete the schedule details.');
          return;
        }
        
        const sched_data = 
        `
        <div class="input-group mx-auto w-100 schedule-item">

        <span class="input-group-text text-warning">Selected Day:</span>
        <span class="input-group-text bg-warning-subtle">${day_of_week}</span>

        <span class="input-group-text text-success">Start Time:</span>
        <span class="input-group-text bg-success-subtle">${start_time}</span>

        <span class="input-group-text text-danger">End Time:</span>
        <span class="input-group-text bg-danger-subtle">${end_time}</span>

        <button class="btn btn-danger text-warning remove-sched" type="button" id="removeSched">-</button>

        </div>
        `

        $('#bodySched').append(sched_data);

        scheduleList.push({
          day_of_week: day_of_week,
          start_time: start_time,
          end_time: end_time
        });

        console.log(scheduleList);
      });


      // REMOVE SCHEDULE
      $('#bodySched').on('click', '.remove-sched', function () {
        var index = $(this).parent().index();
        scheduleList.splice(index, 1);
        $(this).parent().remove();
        console.log(scheduleList);
      });

      // CLEAR SCHEDULE
      $('#btnClear').click(function () {

        $('#mod_addDocSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });
        $('#bodySched').empty();
        scheduleList = [];
      });

      // SAVE SCHEDULE
      $('#btnSaveSched').click(function () {

        $('#mod_addDocSched').modal('hide');
        $('#mod_addDocSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });

        var doctor_sched = JSON.stringify(scheduleList);
        $('#doctor_sched').val(doctor_sched);

        console.log('Saved Schedules:', doctor_sched);

      });

      // CREATE DOCTOR
      $('#frm_addDoc').submit(function (e) {

        e.preventDefault();

        var first_name = $('#first_name').val();
        var middle_name = $('#middle_name').val();
        var last_name = $('#last_name').val();
        var contact = $('#contact').val();
        var doctor_sched = $('#doctor_sched').val();

        if (!doctor_sched || doctor_sched === '[]') {
          alert('PLEASE COMPLETE SCHEDULE');
          return;
        }

        var doctor_data = {
          first_name: first_name,
          middle_name: middle_name,
          last_name: last_name,
          contact: contact,
          doctor_sched: doctor_sched
        }

        console.log('click submit', doctor_data);

        $.ajax({

          type: 'POST',
          url: 'handles/doctors/create_doctor.php',
          data: doctor_data,
          success: function (response) {
            console.log('FUNCTION DATA:', doctor_data);
            console.log(response);
            loadDoctors();
            closeModal();
            $('#bodySched').empty();
            scheduleList = [];
          },
          error: function (error) {
            console.log('ADD DOCTOR ERROR:', error);
            console.log('ERROR: DOCTOR DATA:', doctor_data);
          }
        });
      });

      // EDIT DOCTOR
      $('#tbodyDoctors').on('click', '#callEdit', function () {
        var doctor_id = $(this).closest("td").data('doctor-id');
        var doctor_avail_id = $(this).closest("td").data('doctor-avail-id');
        console.log("doctor id on edit click:", doctor_id);
        console.log("doctor avail id on edit click:", doctor_avail_id);

        $('#e_doctor_id').val(doctor_id);

        console.log("input doctor id on edit", $('#e_doctor_id').val());

        $.ajax({
          type: 'GET',
          url: 'handles/doctors/get_doctor.php',
          data: { doctor_id: doctor_id, doctor_avail_id: doctor_avail_id },
          dataType: 'JSON',
          success: function(response) {
            console.log("get doctor success function:", response);

            $('#e_bodySched').empty();
            editScheduleList = [];

            response.data.forEach(function(schedule) {
              const sched_data = `
              <div class="input-group mx-auto w-100 schedule-item">

              <span class="input-group-text text-warning">Selected Day:</span>
              <span class="input-group-text bg-warning-subtle">${schedule.avail_date}</span>

              <span class="input-group-text text-success">Start Time:</span>
              <span class="input-group-text bg-success-subtle">${schedule.avail_start_time}</span>

              <span class="input-group-text text-danger">End Time:</span>
              <span class="input-group-text bg-danger-subtle">${schedule.avail_end_time}</span>

              <button class="btn btn-danger text-warning remove-sched" type="button" id="removeSched">-</button>

              </div>
              `;
              $('#e_bodySched').append(sched_data);

              editScheduleList.push({
                avail_day: schedule.avail_date,
                avail_start_time: schedule.avail_start_time,
                avail_end_time: schedule.avail_end_time
              });

            });

            $('#e_first_name').val(response.data[0].first_name);
            $('#e_middle_name').val(response.data[0].middle_name);
            $('#e_last_name').val(response.data[0].last_name);
            $('#e_contact').val(response.data[0].contact);
            $('#e_avail_day').val(response.data[0].avail_day);
            $('#e_avail_start_time').val(response.data[0].avail_start_time);
            $('#e_avail_end_time').val(response.data[0].avail_end_time);
            // $('#mod_editDocSched').modal('show');

            console.log('grabbed schedule list', editScheduleList);
          },
          error: function(error) {
            console.log("get doctor error:", error);
          }
        });
      });

      // SAVE EDITED SCHEDULE
      $('#e_addSched').click(function () {
        var avail_day = $('#e_avail_day').val();
        var avail_start_time = $('#e_avail_start_time').val();
        var avail_end_time = $('#e_avail_end_time').val();

        if (!avail_day || !avail_start_time || !avail_end_time) {
          alert('Please complete the schedule details.');
          return;
        }

        const sched_data = `
        <div class="input-group mx-auto w-100 schedule-item">

        <span class="input-group-text text-warning">Selected Day:</span>
        <span class="input-group-text bg-warning-subtle">${avail_day}</span>

        <span class="input-group-text text-success">Start Time:</span>
        <span class="input-group-text bg-success-subtle">${avail_start_time}</span>

        <span class="input-group-text text-danger">End Time:</span>
        <span class="input-group-text bg-danger-subtle">${avail_end_time}</span>

        <button class="btn btn-danger text-warning remove-sched" type="button" id="removeSched">-</button>

        </div>
        `

        $('#e_bodySched').append(sched_data);

        editScheduleList.push({
          avail_day: avail_day,
          avail_start_time: avail_start_time,
          avail_end_time: avail_end_time
        });

        console.log(editScheduleList);
      });

      // REMOVE EDITED SCHEDULE
      $('#e_bodySched').on('click', '.remove-sched', function () {
        var index = $(this).parent().index();
        editScheduleList.splice(index, 1);
        $(this).parent().remove();
        console.log(editScheduleList);
      });

      // CLEAR EDITED SCHEDULE
      $('#e_btnClear').click(function () {
        $('#mod_editDocSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });
        $('#e_bodySched').empty();
        editScheduleList = [];
      });

      // SAVE EDITED SCHEDULE
      $('#e_btnSaveSched').click(function () {
        $('#mod_editDocSched').modal('hide');
        $('#mod_editDocSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });

        var avail_dates = JSON.stringify(editScheduleList);
        $('#e_avail_dates').val(avail_dates);

        console.log('Saved Schedules:', avail_dates);
      });

      // UPDATE DOCTOR
      $('#frm_editDoc').submit(function (e) {

        e.preventDefault();

        var avail_dates = JSON.stringify(editScheduleList);
        $('#e_avail_dates').val(avail_dates);

        var doctor_id = $('#e_doctor_id').val(); // Assuming you have a hidden input for doctor_id in the modal
        var first_name = $('#e_first_name').val();
        var middle_name = $('#e_middle_name').val();
        var last_name = $('#e_last_name').val();
        var contact = $('#e_contact').val();
        var avail_dates = $('#e_avail_dates').val();

        if (!avail_dates || avail_dates === '[]') {
          alert('PLEASE COMPLETE SCHEDULE');
          return;
        }

        var doctor_data = {
          doctor_id: doctor_id,
          first_name: first_name,
          middle_name: middle_name,
          last_name: last_name,
          contact: contact,
          avail_dates: avail_dates
        };

        console.log('click submit', doctor_data);

        $.ajax({

          type: 'POST',
          url: 'handles/doctors/update_doctor.php',
          data: doctor_data,
          success: function (response) {
            console.log('FUNCTION DATA:', doctor_data);
            console.log(response);
            loadDoctors();
            closeModal();
          },
          error: function (error) {
            console.log('UPDATE DOCTOR ERROR:', error);
            console.log('UPDATE ERROR: DOCTOR DATA:', doctor_data);
          }
        });
      });

      // CALL EDIT SCHEDULE
      $('#e_callSetSched').click(function () {

        new bootstrap.Modal($('#mod_editDocSched')).show();

      });


      // CLOSE MODAL FUNCTION
      function closeModal() {

        $('#mod_addDoc .btn-close').click();
        $('#mod_editDoc .btn-close').click();
        $('#mod_delDoc .btn-close').click();
        clearFields();
      } // END CLOSE MODAL FUNCTION

      // CLEAR FIELDS FUNCTION
      function clearFields() {

        $('#first_name').val('');
        $('#middle_name').val('');
        $('#last_name').val('');
        $('#contact').val('');

        $('#del_user_input').val('');
      } // END CLEAR FIELDS FUNCTION

      // ON CLOSE MODAL
      $('#mod_addDoc').on('hidden.bs.modal', function () {

        clearFields();
      });

      $('#mod_editDoc').on('hidden.bs.modal', function () {

        clearFields();
      });

      $('#mod_delDoc').on('hidden.bs.modal', function () {

        clearFields();
      }); // END ON CLOSE MODAL

    }); // END READY
  </script>
  <!-- end jQuery script -->