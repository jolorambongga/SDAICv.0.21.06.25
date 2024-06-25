<!-- start schedule modal -->
<div class="modal fade" id="mod_editServSched" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_editServSchedLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="mod_editServSchedLabel">Edit Services' Schedule</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" id="e_avail_dates" name="e_avail_dates">
        <input type="hidden" id="e_service_id" name="e_service_id">

        <pre></pre>


        <!-- input group -->
        <div class="input-group mb-3">

          <label class="input-group-text bg-warning-subtle" for="e_avail_day">Select Day</label>
          <select class="form-select" id="e_avail_day">
            <option selected></option>
            <option>Sunday</option>
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
          </select>


          <!-- sun start time -->
          <label class="input-group-text bg-success-subtle" for="e_avail_start_time">Start Time</label>
          <select class="form-select" id="e_avail_start_time">
            <option selected></option>
            <optgroup label="AM">                    
              <option value="9:00">9:00 AM</option>
              <option value="10:00">10:00 AM</option>
              <option value="11:00">11:00 AM</option>
            </optgroup>
            <optgroup label="PM">
              <option value="12:00">12:00 PM</option>
              <option value="13:00">1:00 PM</option>
              <option value="14:00">2:00 PM</option>
              <option value="15:00">3:00 PM</option>
              <option value="16:00">4:00 PM</option>
              <option value="17:00">5:00 PM</option>
            </optgroup>
          </select>
          <!-- sun end time -->
          <label class="input-group-text bg-danger-subtle" for="e_avail_end_time">End Time</label>
          <select class="form-select" id="e_avail_end_time">
            <option selected></option>
            <optgroup label="AM">
              <option value="9:00">9:00 AM</option>
              <option value="10:00">10:00 AM</option>
              <option value="11:00">11:00 AM</option>
            </optgroup>
            <optgroup label="PM">
              <option value="12:00">12:00 PM</option>
              <option value="13:00">1:00 PM</option>
              <option value="14:00">2:00 PM</option>
              <option value="15:00">3:00 PM</option>
              <option value="16:00">4:00 PM</option>
              <option value="17:00">5:00 PM</option>
              <option value="18:00">6:00 PM</option>
            </optgroup>
          </select>

          <button class="btn btn-success text-warning" type="button" id="e_addSched">+</button>
        </div>
        <!-- end input group -->

        <div id="e_bodySched"></div>

      </div>
      <div class="modal-footer">
        <button id="e_btnClear" type="button" class="btn btn-warning">Clear</button>
        <button id="e_btnSaveSched" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- end schedule modal -->

<style>
  .schedule-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .schedule-item .input-group-text,
  .schedule-item .btn {
    flex: 1;
    margin: 2px;
  }
</style>