#include <boost/network/protocol/http/client.hpp>
#include <string>
#include <iostream>

int main()
{
    boost::network::http::client client;
    boost::network::http::client::request request("http://www.example.com");
    request << boost::network::header("Connection", "close");
    boost::network::http::client::response response = client.get(request);

    std::cout << body(response);
}
