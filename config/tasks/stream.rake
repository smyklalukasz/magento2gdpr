class StreamOutputInteractionHandler
	def on_data(_command, stream_name, data, channel)
		$stderr.print data
	end
end

