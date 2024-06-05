<div class="card-header border-0 pt-6" id="filter_sub" style="display: none">
<div class="card-title">
        <div class="row ">
            <div class="w-250px me-3">
                <select class="form-select select2Box" data-control="select2" data-placeholder="Select Employee Name" name="employee_name">
                    <option value="">Select Employee Name</option>
                    @if(isset($employees))
                    @foreach($employees as $employee)
                    <option value="{{$employee->employee_name}}">{{$employee->employee_name}}</option>
                    @endforeach
                    @endif
                </select>
               </div>
               <div class="w-250px me-3">
                <select class="form-select" data-control="select2" data-placeholder="Select Employee Code" name="employee_code">
                    <option  value="">Select Employee Code</option>
                    @if(isset($employees))
                    @foreach($employees as $employee)
                    <option value="{{$employee->employee_code}}">{{$employee->employee_code}}</option>
                    @endforeach
                    @endif
                </select>
               </div>
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
        </div>
</div>
</div>
