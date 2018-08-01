@extends('chief::back._layouts.mail')

@section('preheader')
@endsection

@section('title')
@endsection

@section('content')
    <tr>
        <td bgcolor="#ffffff" align="left" style="padding: 0px 50px 25px 50px; color: #808080; font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;" >
            <p style="margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 21px;">
                contact input mail.
            </p>

            <p style="margin: 40px 0 20px 0;">
                {{ implode(', ', $data) }}
            </p>

            <p style="margin: 0; font-family: Helvetica, Arial, sans-serif; font-size: 10px; font-weight: 100; line-height: 12px;">
            </p>

        </td>
    </tr>

@endsection