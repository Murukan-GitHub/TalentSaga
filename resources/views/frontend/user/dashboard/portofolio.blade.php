@extends('frontend.layout.base')

@section('content')
    <div class="site-main-inner">
        <div class="container">
            <div class="category-layout">
                <div class="category-layout-filter-trigger">
                    <br>
                    <button class="btn btn--tosca">Menu</button>
                </div>
                <div class="category-filter">
                    @include('frontend.partials.dashboard.menu')
                </div>
                <div class="category-content">
                    <h2>Portofolio</h2>

                    <div class="text-right">
                        <button class="btn btn--sm btn--tosca block-half" data-modal="#addPortofolio">Add portofolio</button>
                        <template id="addPortofolio">
                            {!! Form::open(['route' => 'user.dashboard.portofolio.save', 'data-validate']) !!}
                                <div>
                                    <div class="block-half">
                                        <label class="form-label" for="inputPerformanceDate">Date of performance</label>
                                        <input class="form-input" id="inputPerformanceDate" type="text" name="event_date" data-datepicker required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventName">Name of event</label>
                                        <input class="form-input" id="inputEventName" type="text" name="event_name" required>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputExperience">Describe your experience</label>
                                        <textarea class="form-input" id="inputExperience" name="description" rows="5" required></textarea>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventUrl">URL of event</label>
                                        <input class="form-input" id="inputEventUrl" name="url" type="text" required>
                                        <small>Can be video or article review</small>
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputEventYoutubeUrl">Youtube URL of event</label>
                                        <input class="form-input" id="inputEventYoutubeUrl" name="youtube_url" type="text">
                                    </div>

                                    <div class="block-half">
                                        <label class="form-label" for="inputStatus">Status</label>
                                        <select class="form-input" id="inputStatus" name="status" required>
                                        @foreach($baseModel->getStatusOptions() as $key=>$value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <button class="btn btn--tosca" type="submit">Add</button>
                            </form>
                        </template>
                    </div>

                    <div class="table-responsive">
                        <table class="table table--zebra table--dashboard">
                            <thead>
                                <tr>
                                    <th>Event name</th>
                                    <th align="center">Date</th>
                                    <th>URL</th>
                                    <th align="center">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($userPortofolios)
                                @foreach($userPortofolios as $key=>$userPortofolio)
                                    <tr>
                                        <td>{{ $userPortofolio->event_name }}</td>
                                        <td align="center">{{ $userPortofolio->event_date->format(app()->getLocale() == 'de' ? 'd.m.Y' : 'm/d/Y') }}</td>
                                        <td><a href="{{ $userPortofolio->url }}" target="_blank">{{ $userPortofolio->url }}</a></td>
                                        <td align="center">{{ ucwords($userPortofolio->status) }}</td>
                                        <td>
                                            {!! Form::open(['url' => route('user.dashboard.portofolio.delete', ['id' => $userPortofolio->id])]) !!}
                                                <a class="btn btn--tosca" href="{{ route('user.dashboard.portofolio.edit', ['id' => $userPortofolio->id]) }}">Edit</a>
                                                <input type="submit" value="Delete" class="btn btn--tosca" onClick="return confirm('Are you sure?')">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="5" align="center">No Portofolio Yet</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    @if($userPortofolios)
                        @include('frontend.partials.pagination', ['paginator' => $userPortofolios])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- portofolio -->
@stop
