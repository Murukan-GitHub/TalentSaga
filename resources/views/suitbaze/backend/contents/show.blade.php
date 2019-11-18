@extends('backend.layouts.base')

@section('content')
    <nav>
        <h2 class="sr-only">You are here:</h2>
        <ul class="breadcrumb">
            <li><a href="#">Site Management</a></li>
            <li><a href="{{ route('backend.content.index') }}">Content</a></li>
            <li>Detail</li>
        </ul>
    </nav>

    <h1 class="heading">Content "{{ $content->getFormattedValue() }}"</h1>
    <hr />

    <div class="block text-right">
        {!! nav_menu(route("backend.content.edit", ['id'=>$content->id]), 'Update', 'fa-pencil') !!}
        {!! post_nav_menu(route('backend.content.destroy', ['id' => $content->id]), 'Delete', csrf_token(), 'Are you sure?', 'fa-remove') !!}
    </div>

    <div class="bzg">
      <div class="bzg_c" data-col="l12">
        <table id="content-head" class="table table--zebra" cellspacing="0" width="100%">
          <tbody>
              @foreach($content->getBufferedAttributeSettings() as $key=>$val)
                @if ($key != 'content')
                  <tr>
                      <td><b>{{ $val['label'] }}</b></td>
                      <td>
                          {!! $content->renderAttribute($key, [
                              'status' => [
                                  App\Models\Content::PUBLISHED_STATUS => "<span class='label label--blue'>#status#</span>",
                                  App\Models\Content::DRAFT_STATUS => "<span class='label label--lime'>#status#</span>"
                              ]
                          ]) !!}
                      </td>
                  </tr>
                @endif
              @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="bzg">
      <div class="bzg_c" data-col="l12">
        <h3>Content</h3>
        <div style="background-color: #fefefe; border: solid 1px #ddd; padding: 16px;">
        {!! htmlspecialchars_decode($content->content) !!}
        </div>
      </div>
    </div>

    <br><br>

    <div class="bzg">
        <div class="bzg_c" data-col="l12">
            <div class="dashboard-tab dashboard-tab--white block">
                <div class="dashboard-tab__navs">
                    <a class="dashboard-tab-nav text-uppercase is-active" href="#products">Related Products</a>
                </div>
                <div class="dashboard-tab__content is-active" id="products">
                    <div class="bzg">
                        <div class="bzg_c" data-col="l12">
                            <br>
                            <div class="text-right">
                                <a class="btn btn--blue" href="#">
                                    <span class="fa fa-fw fa-plus"></span>
                                    Add New
                                </a>
                            </div>

                            <br><br>
                            <table id="contentproducts" class="table table--zebra"  data-enhance-ajax-table="#">
                              <thead>
                                  <tr>
                                    <td><b>#</b></td>
                                    <td><b>Product</b></td>
                                    <td><b>Menu</b></td>
                                  </tr>
                              </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
