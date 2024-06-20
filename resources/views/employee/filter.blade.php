<div class="card-header border-0 pt-6" id="filter_sub" style="display: none">
<div class="card-title">
        <div class="row ">
            <div class="w-250px me-3">
                <select class="form-select" data-control="select2" data-placeholder="Select Department" name="department">
                    <option  value="">Select Department</option>
                    @if(isset($employees))
                    @foreach($employees as $employee)
                    <option value="{{$employee->department}}">{{$employee->department }}</option>
                    @endforeach
                    @endif
                </select>
               </div>
            <div class="w-250px me-3">
                <select class="form-select select2Box" data-control="select2" data-placeholder="Select Designation" name="designation">
                    <option value="">Select Designation</option>
                    @if(isset($employees))
                    @foreach($employees as $employee)
                    <option value="{{$employee->designation}}">{{$employee->designation}}</option>
                    @endforeach
                    @endif
                </select>
               </div>
               <div class="w-250px me-3">
                <select class="form-select" data-control="select2" data-placeholder="Select State" name="state">
                    <option  value="">Select State</option>
                    @if(isset($employees))
                    @foreach($employees as $employee)
                    <option value="{{$employee->state}}">{{$employee->state}}</option>
                    @endforeach
                    @endif
                </select>
               </div>
               <div class="w-250px me-3">
                <select class="form-select" data-control="select2" data-placeholder="Select City" name="city">
                    <option  value="">Select City</option>
                    @php
                  $uniqueCities = $employees->pluck('city')->unique();
                  @endphp
                    @if(isset($uniqueCities))
                    @foreach($uniqueCities as $city)
                    <option value="{{$city}}">{{$city}}</option>
                    @endforeach
                    @endif
                </select>
               </div>
        </div>
</div>
</div>
