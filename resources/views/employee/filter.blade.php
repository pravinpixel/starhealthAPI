<div class="card-header border-0 pt-6" id="filter_sub" style="display: none">
<div class="card-title">
        <div class="row row-gap-10px">
            <div class="w-200px">
                <select class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Select Department" name="department">
                    <option value="">Select Department</option>
                    @php
                        $uniqueDepartments = $datas->pluck('department')->unique(function ($department) {
                            return strtolower($department); 
                        });
                    @endphp
                    @foreach($uniqueDepartments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
                
               </div>
            <div class="w-200px">
                <select class="form-select select2Box" data-allow-clear="true" data-control="select2" data-placeholder="Select Designation" name="designation">
                    <option value="">Select Designation</option>
                    @php
                        $uniqueDesignations = $datas->pluck('designation')->unique(function ($designation) {
                            return strtolower($designation); 
                        });
                    @endphp
                    @foreach($uniqueDesignations as $designation)
                        <option value="{{ $designation }}">{{ $designation }}</option>
                    @endforeach
                </select>
                
               </div>
               <div class="w-200px">
                <select class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Select State" name="state">
                    <option value="">Select State</option>
                    @php
                        $uniqueStates = $datas->pluck('state')->unique(function ($state) {
                            return strtolower($state);
                        });
                    @endphp
                    @foreach($uniqueStates as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
                
               </div>
               <div class="w-200px">
                <select class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Select City" name="city">
                    <option value="">Select City</option>
                    @php
                        $uniqueCities = $datas->pluck('city')->unique(function ($city) {
                            return strtolower($city); 
                        });
                    @endphp
                    @foreach($uniqueCities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
               </div>
        </div>
</div>
</div>
