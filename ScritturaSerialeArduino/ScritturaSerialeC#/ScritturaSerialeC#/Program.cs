using System;
using System.Collections.Generic;
using System.IO.Ports;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ScritturaSerialeC_
{
    internal class Program
    {
        static void Main(string[] args)
        {
            SerialPort port = new SerialPort("COM3",9600);
            port.Open();




        }
    }
}
