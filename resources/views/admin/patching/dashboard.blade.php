@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-3">
        <form method="get" action="/admin/patching/dashboard">
            <label class="recommended" for="patching_group">{{ trans('cruds.logicalServer.fields.patching_group') }}</label>
            <select name="group" class="form-control select2 {{ $errors->has('patching_group') ? 'is-invalid' : '' }}"
                    name="patching_group" id="patching_group"
                    onchange="this.form.submit()">
                <option value="All">&nbsp;</option>
                @foreach($patching_group_list as $group)
                    <option {{ session()->get('patching_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('panel.menu.patching') }}
    </div>
    <div class="card-body" >
        <table width="100%">
            <tr>
                <td width="20%">
                      <canvas id="doughnut_chart1_div"></canvas>
                </td>
                <td width="80%">
                      <canvas id="bar_chart2_div"></canvas>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/Chart.bundle.js"></script>

<script type="text/javascript">

      const ctx1 = document.getElementById('doughnut_chart1_div').getContext('2d');

      const cfg1 = {
        type: 'doughnut',
        data: {
            labels : ['made','late','undefined'],
            datasets: [
                {
                label: 'Dataset 1',
                data: [
                    @php
                        $made = 0;
                        $late = 0;
                        $undef = 0;
                        foreach($patches as $patch) {
                            if ($patch->next_update==null)
                                $undef++;
                            elseif ($patch->next_update<today())
                                $late++;
                            else
                                $made++;
                        }
                        echo $made;
                        echo ',';
                        echo $late;
                        echo ',';
                        echo $undef;
                    @endphp
                ],
                backgroundColor: ['#4bc0c0','#ff6384','#e4e5e6'],
                }
            ]
        },
        options: {
          responsive: true,
          legend: {
                     display: false,
                 },
        },
    };

      // --------------------------------------------------------------------
// https://www.chartjs.org/docs/latest/samples/bar/stacked.html
      const ctx2 = document.getElementById('bar_chart2_div').getContext('2d');

      const cfg2 = {
        type: 'bar',
        data: {
            labels: [
                <?php
                for($i=-12; $i<=12; $i++) {
                    echo "'";
                    echo Carbon\Carbon::today()->addMonth($i)->format('M y');
                    echo "', ";
                    }
                ?>
            ],
            datasets: [
            {
                label: 'made',
                data: [
                <?php
                for($i=-12; $i<=12; $i++) {
                    $count = 0;
                    $year = today()->addMonth($i)->year;
                    $month = today()->addMonth($i)->month;
                    foreach($patches as $patch) {
                        if (
                            ($patch->update_date!==null) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->update_date)->month===$month) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->update_date)->year===$year)

                        )
                            $count++;
                        }
                    echo $count;
                    echo ", ";
                    }
                ?>
                ],
                backgroundColor: '#4bc0c0',
            },
            {
                label: 'late',
                data: [
                <?php
                for($i=-12; $i<=12; $i++) {
                    $count = 0;
                    $year = today()->addMonth($i)->year;
                    $month = today()->addMonth($i)->month;
                    foreach($patches as $patch) {
                        if (
                            ($patch->next_update!==null) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->next_update)->month==$month) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->next_update)->year==$year) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->next_update)->lt(today())
                            )
                        ) {
                            $count++;
                            }
                        }
                    echo $count;
                    echo ", ";
                    }
                ?>
                ],
                backgroundColor: '#ff6384',
            },
            {
                label: 'plan',
                data: [
                <?php
                for($i=-12; $i<=12; $i++) {
                    $count = 0;
                    $year = today()->addMonth($i)->year;
                    $month = today()->addMonth($i)->month;
                    foreach($patches as $patch) {
                        if (
                            ($patch->next_update!==null) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->next_update)->month==$month) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->next_update)->year==$year) &&
                            (Carbon\Carbon::createFromFormat('d/m/Y',$patch->next_update)->gt(today()))
                        )
                            $count++;
                        }
                    echo $count;
                    echo ", ";
                    }
                ?>
                ],
                backgroundColor: '#e4e5e6',
            },
            ]
        },


        options: {
            responsive: true,
            legend: {
                       display: false,
                   },
           scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }
      };

      // --------------------------------------------------------------------

      window.onload = function() {
          new Chart(ctx1, cfg1);
          new Chart(ctx2, cfg2);
        };

</script>
@endsection
